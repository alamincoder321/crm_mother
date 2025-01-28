<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DesignationtController;
use App\Http\Controllers\BankTransactionController;

Route::fallback(function () {
    return view('error.404');
})->middleware('auth');


// user login route
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [DashboardController::class, 'Logout'])->middleware('auth')->name('logout');

//company profile update
Route::get('/companyProfile', [DashboardController::class, 'companyProfile'])->name('companyProfile');
Route::post('/update-companyProfile', [DashboardController::class, 'updatecompanyProfile'])->name('update.companyProfile');
Route::get('/get-headerInfo', [DashboardController::class, 'getHeaderInfo'])->name('get.headerInfo');

//panel and dashboard route
Route::group(['prefix' => 'panel'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/{panel}', [DashboardController::class, 'panel'])->name('panel.access');
});

// ============================= Control Panel Route ==============================

// user route
Route::get('/user', [UserController::class, 'create'])->name('user.create');
Route::post('/get-user', [UserController::class, 'index'])->name('user.index');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::post('/update-user', [UserController::class, 'update'])->name('user.update');
Route::post('/delete-user', [UserController::class, 'destroy'])->name('user.delete');

// unit route
Route::get('/unit', [UnitController::class, 'create'])->name('unit.create');
Route::post('/get-unit', [UnitController::class, 'index'])->name('unit.index');
Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
Route::post('/update-unit', [UnitController::class, 'update'])->name('unit.update');
Route::post('/delete-unit', [UnitController::class, 'destroy'])->name('unit.delete');

// category route
Route::get('/category', [CategoryController::class, 'create'])->name('category.create');
Route::post('/get-category', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::post('/update-category', [CategoryController::class, 'update'])->name('category.update');
Route::post('/delete-category', [CategoryController::class, 'destroy'])->name('category.delete');

// brand route
Route::get('/brand', [BrandController::class, 'create'])->name('brand.create');
Route::post('/get-brand', [BrandController::class, 'index'])->name('brand.index');
Route::post('/brand', [BrandController::class, 'store'])->name('brand.store');
Route::post('/update-brand', [BrandController::class, 'update'])->name('brand.update');
Route::post('/delete-brand', [BrandController::class, 'destroy'])->name('brand.delete');

// area route
Route::get('/area', [AreaController::class, 'create'])->name('area.create');
Route::post('/get-area', [AreaController::class, 'index'])->name('area.index');
Route::post('/area', [AreaController::class, 'store'])->name('area.store');
Route::post('/update-area', [AreaController::class, 'update'])->name('area.update');
Route::post('/delete-area', [AreaController::class, 'destroy'])->name('area.delete');

// supplier route
Route::get('/supplier', [SupplierController::class, 'create'])->name('supplier.create');
Route::get('/supplierList', [SupplierController::class, 'supplierList'])->name('supplier.list');
Route::post('/get-supplier', [SupplierController::class, 'index'])->name('supplier.index');
Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
Route::post('/update-supplier', [SupplierController::class, 'update'])->name('supplier.update');
Route::post('/delete-supplier', [SupplierController::class, 'destroy'])->name('supplier.delete');

// customer route
Route::get('/customer', [CustomerController::class, 'create'])->name('customer.create');
Route::get('/customerList', [CustomerController::class, 'customerList'])->name('customer.list');
Route::post('/get-customer', [CustomerController::class, 'index'])->name('customer.index');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::post('/update-customer', [CustomerController::class, 'update'])->name('customer.update');
Route::post('/delete-customer', [CustomerController::class, 'destroy'])->name('customer.delete');

// product route
Route::get('/product', [ProductController::class, 'create'])->name('product.create');
Route::get('/productList', [ProductController::class, 'productList'])->name('product.list');
Route::post('/get-product', [ProductController::class, 'index'])->name('product.index');
Route::post('/product', [ProductController::class, 'store'])->name('product.store');
Route::post('/update-product', [ProductController::class, 'update'])->name('product.update');
Route::post('/delete-product', [ProductController::class, 'destroy'])->name('product.delete');


// ======================================== HR Panel =====================================

// department route
Route::get('/department', [DepartmentController::class, 'create'])->name('department.create');
Route::post('/get-department', [DepartmentController::class, 'index'])->name('department.index');
Route::post('/department', [DepartmentController::class, 'store'])->name('department.store');
Route::post('/update-department', [DepartmentController::class, 'update'])->name('department.update');
Route::post('/delete-department', [DepartmentController::class, 'destroy'])->name('department.delete');

// designation route
Route::get('/designation', [DesignationtController::class, 'create'])->name('designation.create');
Route::post('/get-designation', [DesignationtController::class, 'index'])->name('designation.index');
Route::post('/designation', [DesignationtController::class, 'store'])->name('designation.store');
Route::post('/update-designation', [DesignationtController::class, 'update'])->name('designation.update');
Route::post('/delete-designation', [DesignationtController::class, 'destroy'])->name('designation.delete');

// employee route
Route::get('/employee', [EmployeeController::class, 'create'])->name('employee.create');
Route::get('/employeeList', [EmployeeController::class, 'employeeList'])->name('employee.list');
Route::post('/get-employee', [EmployeeController::class, 'index'])->name('employee.index');
Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
Route::post('/update-employee', [EmployeeController::class, 'update'])->name('employee.update');
Route::post('/delete-employee', [EmployeeController::class, 'destroy'])->name('employee.delete');

//salary generate route
Route::get('/salary', [SalaryController::class, 'create'])->name('salary.create');
Route::get('/salaryList', [SalaryController::class, 'salaryList'])->name('salary.list');
Route::post('/check-salary', [SalaryController::class, 'checkSalary'])->name('salary.check');
Route::post('/get-salary', [SalaryController::class, 'index'])->name('salary.index');
Route::post('/salary', [SalaryController::class, 'store'])->name('salary.store');
Route::post('/update-salary', [SalaryController::class, 'update'])->name('salary.update');
Route::post('/delete-salary', [SalaryController::class, 'destroy'])->name('salary.delete');


// ============================= Account Panel Route ==============================
// account head route
Route::get('/accounthead', [AccountHeadController::class, 'create'])->name('accounthead.create');
Route::post('/get-accounthead', [AccountHeadController::class, 'index'])->name('accounthead.index');
Route::post('/accounthead', [AccountHeadController::class, 'store'])->name('accounthead.store');
Route::post('/update-accounthead', [AccountHeadController::class, 'update'])->name('accounthead.update');
Route::post('/delete-accounthead', [AccountHeadController::class, 'destroy'])->name('accounthead.delete');

// bank route
Route::get('/bank', [BankController::class, 'create'])->name('bank.create');
Route::post('/get-bank', [BankController::class, 'index'])->name('bank.index');
Route::post('/bank', [BankController::class, 'store'])->name('bank.store');
Route::post('/update-bank', [BankController::class, 'update'])->name('bank.update');
Route::post('/delete-bank', [BankController::class, 'destroy'])->name('bank.delete');

// expense route
Route::get('/expense', [TransactionController::class, 'expense'])->name('expense.create');
Route::get('/income', [TransactionController::class, 'income'])->name('income.create');
Route::post('/get-transaction', [TransactionController::class, 'index'])->name('transaction.index');
Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
Route::post('/update-transaction', [TransactionController::class, 'update'])->name('transaction.update');
Route::post('/delete-transaction', [TransactionController::class, 'destroy'])->name('transaction.delete');

// bankTransaction route
Route::get('/bankTransaction', [BankTransactionController::class, 'create'])->name('bankTransaction.create');
Route::post('/get-bankTransaction', [BankTransactionController::class, 'index'])->name('bankTransaction.index');
Route::post('/bankTransaction', [BankTransactionController::class, 'store'])->name('bankTransaction.store');
Route::post('/update-bankTransaction', [BankTransactionController::class, 'update'])->name('bankTransaction.update');
Route::post('/delete-bankTransaction', [BankTransactionController::class, 'destroy'])->name('bankTransaction.delete');

// receive route
Route::get('/receive', [ReceiveController::class, 'create'])->name('receive.create');
Route::post('/get-receive', [ReceiveController::class, 'index'])->name('receive.index');
Route::post('/receive', [ReceiveController::class, 'store'])->name('receive.store');
Route::post('/update-receive', [ReceiveController::class, 'update'])->name('receive.update');
Route::post('/delete-receive', [ReceiveController::class, 'destroy'])->name('receive.delete');

// payment route
Route::get('/payment', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/get-payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
Route::post('/update-payment', [PaymentController::class, 'update'])->name('payment.update');
Route::post('/delete-payment', [PaymentController::class, 'destroy'])->name('payment.delete');