<?php namespace FI\Storage\Eloquent\Repositories;

use FI\Storage\Eloquent\Models\Invoice;
use FI\Classes\Date;

class InvoiceRepository implements \FI\Storage\Interfaces\InvoiceRepositoryInterface {

	public function all()
	{
		return Invoice::all();
	}

	public function getPagedByStatus($page = 1, $numPerPage = null, $status = 'all')
	{
		\DB::getPaginator()->setCurrentPage($page);

		$invoice = Invoice::with(array('amount', 'client'));

		switch ($status)
		{
			case 'draft':
			return $invoice->draft()->paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
			break;
			case 'sent':
			return $invoice->sent()->paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
			break;
			case 'paid':
			return $invoice->paid()->paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
			break;
			case 'canceled':
			return $invoice->canceled()->paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
			break;
			default:
			return $invoice->paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
		}
	}

	public function find($id)
	{
		return Invoice::with('items.amount')->find($id);
	}

	public function findByUrlKey($urlKey)
	{
		return Invoice::where('url_key', $urlKey)->first();
	}
	
	public function create($clientId, $createdAt, $invoiceGroupId, $userId, $invoiceStatusId)
	{
		$invoiceGroup = \App::make('FI\Storage\Interfaces\InvoiceGroupRepositoryInterface');

		return Invoice::create(
			array(
				'client_id'         => $clientId,
				'created_at'        => Date::unformat($createdAt),
				'due_at'            => Date::incrementDateByDays($createdAt, \Config::get('fi.invoicesDueAfter')),
				'invoice_group_id'  => $invoiceGroupId,
				'number'            => $invoiceGroup->generateNumber($invoiceGroupId),
				'user_id'           => $userId,
				'invoice_status_id' => $invoiceStatusId,
				'url_key'           => str_random(32)
				)
			)->id;
	}
	
	public function update($invoiceId, $createdAt, $dueAt, $number, $invoiceStatusId)
	{
		$invoice = Invoice::find($invoiceId);

		$invoice->fill(
			array(
				'created_at'        => Date::unformat($createdAt),
				'due_at'            => Date::unformat($dueAt),
				'number'            => $number,
				'invoice_status_id' => $invoiceStatusId
			)
		);

		$invoice->save();
	}
	
	public function delete($id)
	{
		$invoice = Invoice::find($id);

		foreach ($invoice->items as $item)
		{
			$item->amount->delete();
			$item->delete();
		}

		foreach ($invoice->taxRates as $taxRate)
		{
			$taxRate->delete();
		}

		$invoice->amount->delete();

		$invoice->delete();
	}
	
}