<?php
/**
 * Job generate lease bill
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Jobs;

use App\Services\BillService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
/**
 *  Generate lease bill class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class GenerateLeaseBills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $search;

    /**
     * Create a new job instance.
     *
     * @param array $search Lease
     *
     * @return void
     */
    public function __construct($search)
    {
        $this->search = $search;
    }

    /**
     * Execute the job.
     *
     * @param BillService $bill Bill
     *
     * @return void
     */
    public function handle(BillService $bill)
    {
        $bill->generateBills($this->search);
    }
}
