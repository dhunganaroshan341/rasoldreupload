<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartsOfAccountController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientServiceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Demo\UserController;
use App\Http\Controllers\DemoRoutesController;
use App\Http\Controllers\DemoWeightController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePayrollController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseModalController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeModalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LedgerClientServiceController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NewTransactionController;
use App\Http\Controllers\OurServiceLedgerController;
use App\Http\Controllers\OurServicesController;
use App\Http\Controllers\OutStandingInvoiceController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\TransactionController;
use App\Livewire\ClientForm;
use App\Livewire\ExpenseCategoryCrud;
use App\Livewire\IncomeCategoryCrud;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/dashboard', [DashboardController::class,'index'])->name('dashbaord.index');
// Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'storeLogin'])->name('store.login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('storeRegister');
Route::get('/home', [AuthController::class, 'login'])->name('guest.home');

// Admin Routes
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/user/edit/{id}', [AuthController::class, 'edit'])->name('editUser');
    Route::post('/user/edit/{id}', [AuthController::class, 'update'])->name('updateUser');
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.default');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('OurServices', OurServicesController::class);
    Route::resource('ourservices', OurServicesController::class);
    Route::resource('/ledger', LedgerController::class);
    Route::resource('/ledger-client-service', LedgerClientServiceController::class);
    Route::get('/ledger-client-service/{client_id}/all', [LedgerClientServiceController::class, 'index'])->name('ledgerClientService.index');
    // ledger for our services
    Route::resource('/ledger-ourservice', OurServiceLedgerController::class);
    // generetate invoice  by selecting  multiple items
    Route::get('/get-multiple-details', [LedgerController::class, 'getMultipleDetails'])->name('ledger.mulltiple-details');
    // Route::get('/ledger/{client_service_id}', [LedgerController::class, 'clientServiceIndex'])->name('ledger.clientService.index');
    Route::get('/ledger/singleEntity/{ledger_id}', [LedgerController::class, 'showSingleEntity'])->name('ledger.showSingleEntity');
    Route::get('/setup', [AdminController::class, 'setup'])->name('setup');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    // invoices or bills to be calculated
    Route::resource('outstanding-invoices', OutStandingInvoiceController::class);
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.index');
    Route::post('/invoice/generate', [InvoiceController::class, 'generate'])->name('invoice.generate');

    // Route for downloading the invoice
    Route::get('/invoice/download/{id}', [InvoiceController::class, 'downloadInvoice'])->name('invoice.download');

    // Route for showing the invoice details
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Route for editing the invoice
    Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    // clients section
    Route::resource('clients', ClientController::class);

    // for livewire clients new crud operation
    // Route::get('clients/edit/{id}', ClientForm::class)->name('clients.edit');
    // Route::get('clients/create', ClientForm::class)->name('clients.create');
    // Route::put('clients/update/{id}', ClientForm::class)->name('clients.update'); // Change GET to PUT

    // client service section
    // all the client-id supposed to mean client_service_id , have to be changed for
    // better readibailit
    Route::get('client/{client_id}/services', [ClientServiceController::class, 'index'])->name('ClientServices.index');
    Route::get('client/service/create/{client_id}', [ClientServiceController::class, 'create'])->name('ClientServices.create');
    Route::get('client/service/store/{client_id}', [ClientServiceController::class, 'create'])->name('ClientServices.store');
    Route::get('client/service/edit/{client_service_id}', [ClientServiceController::class, 'edit'])->name('ClientServices.edit');
    Route::put('client/service/update/{id}', [ClientServiceController::class, 'update'])->name('ClientServices.update');
    Route::delete('client/service/delete/{client_service_id}/', [ClientServiceController::class, 'destroy'])->name('ClientServices.destroy');
    // create individual client one client one service at a time
    Route::get('client/service/create', [ClientServiceController::class, 'createSingleClientService'])->name('createSingleClientService.create');
    Route::post('client/service/create', [ClientServiceController::class, 'createSingleClientService'])->name('createSingleClientService.store');
    // Route::resource('clientservices', ClientServiceController::class);
    // end of client services
    // Override 'create' and 'edit' to use Livewire components
    //
    // Route::get('clients/create', ClientsForm::class)->name('clients.create');
    // Route::get('clients/{client}/edit', ClientForm::class)->name('clients.edit');
    // end of clients section
    //ourservices
    Route::resource('contracts', ContractController::class);
    Route::get('contracts/custom/create', [CustomContractController::class, 'create'])->name('contracts.create.custom');
    Route::post('contracts/custom/store', [CustomContractController::class, 'store'])->name('contracts.custom.store');

    Route::get('invoice', function () {
        return view('invoice.index');
    });
    Route::get('convertkg/{input}', [DemoWeightController::class, 'get'])->name('getkg');
    //transactions
    // transactions, income-category
    Route::get('/transaction-categories', function () {
        return view('dashboard.incomes.livewire-income-category');
    }
    );
    Route::get('/income/categories', IncomeCategoryCrud::class)->name('income-categories');
    Route::get('/expense/categories', ExpenseCategoryCrud::class)->name('expense-categories');
    Route::resource('employees', EmployeeController::class);
    // Route::post('employee/payroll/store', [EmployeeController::class, 'storePayroll'])->name('employee.payroll');
    Route::post('employee/payroll', [EmployeePayrollController::class, 'storePayroll'])->name('employee.payroll');
    Route::post('employee/payroll', [EmployeePayrollController::class, 'storePayroll'])->name('employee.payroll.store');
    Route::post('employee/payroll/update', [EmployeePayrollController::class, 'updatePayroll'])->name('employee.payroll.update');
    Route::delete('employee/payroll/delete/{id}', [EmployeePayrollController::class, 'destroy'])->name('employee.payroll.delete');
    Route::get('storeEmployeeSalary/{id}', [EmployeePayrollController::class, 'store'])->name('employeePayrollStore');
    // Route::resource('employeePayroll', EmployeePayrollController::class);
    // //
    Route::resource('transactions', TransactionController::class);
    Route::resource('transaction', NewTransactionController::class);
    Route::get('/export-transactions', [TransactionController::class, 'export'])->name('transactions.export');
    // Route::get('/export-transactions-table', [TransactionController::class, 'exportView'])->name('transactions.export');
    //income
    Route::resource('incomes', IncomeController::class);
    Route::post('incomeStoreInModal', [IncomeController::class, 'storeIncomeModal'])->name('incomeModal.store');
    Route::post('/client/income/', [IncomeModalController::class, 'storeIncomeFromClient'])->name('clientIncomeModal.store');

    // In web.php (routes file)
    //
    Route::resource('expenses', ExpenseController::class);
    Route::get('/expenses/edit/{id}', [ExpenseController::class, 'editInModal'])->name('expenses.editInModal');
    Route::put('/expenses/{id}', [ExpenseController::class, 'updateOnModal'])->name('expenses.updateOnModal');
    // Route::get('/expenses/{id}/edit', [ExpenseController::class, 'editInModal'])->name('expenses.editInModal');
    Route::post('/client/expense/', [ExpenseModalController::class, 'storeIncomeFromClient'])->name('clientExpenseModal.store');

    //end of transactions

    // charts of accounts
    Route::resource('coa', ChartsOfAccountController::class);
});
Route::middleware(['admin.auth'])->group(function () {
    Route::get('logs', [LogController::class, 'index']);
});
Route::get('faq', function () {
    return view('component.faq');
});

Route::get('faq2', function () {
    return view('component.faq');
});
Route::get('faq3', function () {
    return view('component.faq');
});

Route::get('card', function () {
    return view('component.card');
});
// Component Route
// Route::get('/component/{name}', [DemoRoutesController::class, 'show'])->name('component.show');

// Client Management Routes

Route::get('new_login', function () {
    return view('Auth.Login');
});
Route::get('Demohome', function () {
    return view('dashboard.OurServices.index');
})->name('home');
//our services

Route::resource('ServiceCategory', ServiceCategoryController::class);

//demo tests
Route::get('dateget/{year}/{month}/{day}', function () {
    return view('demo.DemoNepaliDateShower', compact('year', 'month', 'day'));
});
// Route::group(['prefix' => 'demo'], function () {
//     Route::get('/users', 'App\Http\Controllers\Demo\UserController@index'); // List all users
//     Route::post('/users', 'App\Http\Controllers\Demo\UserController@store'); // Create a new user
//     Route::get('/users/{id}', 'App\Http\Controllers\Demo\UserController@show'); // Get a specific user
//     Route::put('/users/{id}', 'App\Http\Controllers\Demo\UserController@update'); // Update a user
//     Route::delete('/users/{id}', 'App\Http\Controllers\Demo\UserController@destroy'); // Delete a user
// });
// other components demo
Route::get('product_detail', function () {
    return view('component.product_detail');
});
Route::get('product', function () {
    return view('component.product');
});
Route::get('client-card', function () {
    $client = App\Models\Client::all();

    return view('components.client-card', compact('client'));
});
//end of demos
