<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TableArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    /**
     * Display a listing of the resource (floor plan view).
     */
    public function index(Request $request)
    {
        $outlet = Auth::user()->currentOutlet;

        $areas = TableArea::where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['tables' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->get();

        // Statistics
        $stats = [
            'total' => Table::where('outlet_id', $outlet->id)->count(),
            'available' => Table::where('outlet_id', $outlet->id)->where('status', 'available')->count(),
            'occupied' => Table::where('outlet_id', $outlet->id)->where('status', 'occupied')->count(),
            'reserved' => Table::where('outlet_id', $outlet->id)->where('status', 'reserved')->count(),
        ];

        return view('tables.index', compact('areas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlet = Auth::user()->currentOutlet;
        $areas = TableArea::where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tables.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_area_id' => 'required|exists:table_areas,id',
            'number' => 'required|string|max:20',
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        $outlet = Auth::user()->currentOutlet;

        // Verify table number is unique for outlet
        if (Table::where('outlet_id', $outlet->id)->where('number', $validated['number'])->exists()) {
            return back()->withErrors(['number' => 'Nomor meja sudah digunakan.'])->withInput();
        }

        // Verify area belongs to outlet if provided
        if (!empty($validated['table_area_id'])) {
            TableArea::where('id', $validated['table_area_id'])
                ->where('outlet_id', $outlet->id)
                ->firstOrFail();
        }

        // Get max sort_order
        $maxSort = Table::where('outlet_id', $outlet->id)->max('sort_order') ?? 0;

        // Generate QR code URL
        $qrUrl = $this->generateQrUrl($outlet, $validated['number']);

        $table = Table::create([
            'outlet_id' => $outlet->id,
            'table_area_id' => $validated['table_area_id'] ?? null,
            'number' => $validated['number'],
            'name' => $validated['name'] ?? null,
            'capacity' => $validated['capacity'],
            'status' => 'available',
            'qr_code' => $qrUrl,
            'sort_order' => $maxSort + 1,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('tables.index')
            ->with('success', "Meja {$table->number} berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        $this->authorizeOutlet($table);

        $outlet = Auth::user()->currentOutlet;
        $areas = TableArea::where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tables.edit', compact('table', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $this->authorizeOutlet($table);

        $validated = $request->validate([
            'table_area_id' => 'required|exists:table_areas,id',
            'number' => 'required|string|max:20',
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        $outlet = Auth::user()->currentOutlet;

        // Verify table number is unique for outlet (except current)
        if (Table::where('outlet_id', $outlet->id)
            ->where('number', $validated['number'])
            ->where('id', '!=', $table->id)
            ->exists()) {
            return back()->withErrors(['number' => 'Nomor meja sudah digunakan.'])->withInput();
        }

        // Verify area belongs to outlet if provided
        if (!empty($validated['table_area_id'])) {
            TableArea::where('id', $validated['table_area_id'])
                ->where('outlet_id', $outlet->id)
                ->firstOrFail();
        }

        // Regenerate QR if number changed
        if ($validated['number'] !== $table->number) {
            $validated['qr_code'] = $this->generateQrUrl($outlet, $validated['number']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $table->update($validated);

        return redirect()->route('tables.index')
            ->with('success', "Meja {$table->number} berhasil diperbarui.");
    }

    /**
     * Update table status.
     */
    public function updateStatus(Request $request, Table $table)
    {
        $this->authorizeOutlet($table);

        $validated = $request->validate([
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update(['status' => $validated['status']]);

        $statusLabels = [
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'reserved' => 'Direservasi',
            'maintenance' => 'Maintenance',
        ];

        return back()->with('success', "Status meja {$table->number} diubah menjadi {$statusLabels[$validated['status']]}.");
    }

    /**
     * Regenerate QR code for a table.
     */
    public function regenerateQr(Table $table)
    {
        $this->authorizeOutlet($table);

        $outlet = Auth::user()->currentOutlet;
        $qrUrl = $this->generateQrUrl($outlet, $table->number);

        $table->update(['qr_code' => $qrUrl]);

        return back()->with('success', "QR code untuk meja {$table->number} berhasil diperbarui.");
    }

    /**
     * Download QR code as image.
     */
    public function downloadQr(Table $table)
    {
        $this->authorizeOutlet($table);

        $outlet = Auth::user()->currentOutlet;
        $qrUrl = route('qr.menu', [$outlet->slug, $table->qr_code]);

        $qrCode = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->generate($qrUrl);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', "attachment; filename=\"qr-meja-{$table->number}.svg\"");
    }

    /**
     * Download all QR codes as ZIP.
     */
    public function downloadAllQrs()
    {
        $outlet = Auth::user()->currentOutlet;
        $tables = Table::where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        if ($tables->isEmpty()) {
            return back()->with('error', 'Tidak ada meja untuk diunduh.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'qr-codes-' . $outlet->slug . '-' . now()->format('YmdHis') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($tables as $table) {
                $qrUrl = route('qr.menu', [$outlet->slug, $table->qr_code]);
                $qrCode = QrCode::format('svg')
                    ->size(400)
                    ->margin(2)
                    ->generate($qrUrl);

                $fileName = "meja-{$table->number}.svg";
                $zip->addFromString($fileName, $qrCode);
            }
            $zip->close();

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membuat file ZIP.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $this->authorizeOutlet($table);

        // Check if table has active orders
        $activeOrder = $table->currentOrder();
        if ($activeOrder) {
            return back()->with('error', 'Tidak dapat menghapus meja yang sedang memiliki order aktif.');
        }

        $tableNumber = $table->number;
        $table->delete();

        return redirect()->route('tables.index')
            ->with('success', "Meja {$tableNumber} berhasil dihapus.");
    }

    /**
     * Generate unique QR code string for table.
     */
    private function generateQrUrl($outlet, string $tableNumber): string
    {
        // Generate unique random string for security
        return strtoupper($tableNumber) . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    /**
     * Authorize that the resource belongs to current outlet.
     */
    private function authorizeOutlet(Table $table): void
    {
        $outlet = Auth::user()->currentOutlet;
        if ($table->outlet_id !== $outlet->id) {
            abort(403, 'Unauthorized access.');
        }
    }
}
