<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Imports\TransactionsImport;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\Income;
use App\Models\OurServices;
use App\Services\TransactionServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    protected $exportedTransaction;

    protected $start_date;

    protected $end_date;

    protected $user;

    public function index(Request $request)
    {
        $this->user = Auth::user();
        $transactionService = new TransactionServiceProvider;
        // get all the client name and
        $clientServices = ClientService::with(['client', 'service'])->get();
        // Get start date and end date from request, defaulting to current month if not provided
        $startDate = $request->input('start_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::now()->endOfMonth()->toDateString();

        $this->start_date = $startDate;
        $this->end_date = $endDate;
        // Fetch merged transactions within the date range
        $mergedTransactions = $transactionService->getTransactions($startDate, $endDate);

        // Paginate the merged transactions array
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;

        // Slice the array to get only the current page's items
        $currentItems = array_slice($mergedTransactions, ($currentPage - 1) * $perPage, $perPage);

        // Initialize the paginator with the sliced items and total count
        $paginatedTransactions = new LengthAwarePaginator($currentItems, count($mergedTransactions), $perPage);
        $paginatedTransactions->setPath($request->url());

        // Add start_date and end_date to pagination links
        $paginatedTransactions->appends(['start_date' => $startDate, 'end_date' => $endDate]);

        // Total number of pages
        $totalPages = $paginatedTransactions->lastPage();

        // Other data you want to pass to the view
        $startingFigure = $transactionService->getAmount($startDate);
        $endingFigure = $transactionService->getAmount($endDate);
        $startingAmount = $transactionService->getAmount($startDate);
        $totalBalance = $transactionService->totalBalance($startDate, $endDate);
        $allData = $transactionService->getAllData();
        // find clientService then pass it
        // GETTIN ALL THE CLIENTS AND OUR SERVICES
        $incomes = Income::all();
        $ourServices = OurServices::all();
        $clients = Client::all();

        // dd($incomes);
        return view('dashboard.transactions.index', compact('clients', 'ourServices', 'clientServices', 'mergedTransactions', 'paginatedTransactions', 'allData', 'startDate', 'endDate', 'startingAmount', 'startingFigure', 'endingFigure', 'totalPages'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create($type)
    {
        // Logic for creating new transactions (not implemented in the example)
        return view('dashboard.transactions.create', compact('type'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        // Logic for storing new transactions (not implemented in the example)
    }

    public function export(Request $request, TransactionServiceProvider $transactionService)
    {
        // Retrieve start and end dates from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Use the service provider to prepare data for export
        $exportData = $transactionService->prepareExportData($startDate, $endDate);

        // Generate a dynamic filename with timestamp
        $filename = 'transactions_'.date('Ymd_His').'.xlsx';

        // Return Excel download response
        return Excel::download(new TransactionsExport($exportData), $filename);
        // error came to check:return dd($transactionService->getExportTransactions($startDate, $endDate));
    }

    public function import(Request $request)
    {
        // Validate the request, if necessary
        // $request->validate([
        //     'file' => 'required|file|mimes:xlsx,csv'
        // ]);

        // Handle the file upload
        $file = $request->file('file');

        // Process the import using Laravel Excel
        Excel::import(new TransactionsImport, $file);

        // Optionally, return a response
        return redirect()->back()->with('success', 'Data imported successfully!');
    }

    public function show($id)
    {
        // Fetch the transaction data by ID

    }
    // Other methods like show, edit, update, destroy can be implemented similarly...
}
