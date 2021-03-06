<?php

use FI\Storage\Interfaces\EmailTemplateRepositoryInterface;
use FI\Validators\EmailTemplateValidator;

class EmailTemplateController extends \BaseController {
	
	protected $emailTemplate;
	protected $validator;
	
	public function __construct(EmailTemplateRepositoryInterface $emailTemplate, EmailTemplateValidator $validator)
	{
		$this->emailTemplate = $emailTemplate;
		$this->validator = $validator;
	}

	/**
	 * Display paginated list
	 * @param  string $status
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$emailTemplates = $this->emailTemplate->getPaged(Input::get('page'));

		return View::make('email_templates.index')
		->with('emailTemplates', $emailTemplates);
	}

	/**
	 * Display form for new record
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return View::make('email_templates.form')
		->with('editMode', false);
	}

	/**
	 * Validate and handle new record form submission
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		$input = Input::all();

		if (!$this->validator->validate($input))
		{
			return Redirect::route('emailTemplates.create')
			->with('editMode', false)
			->withErrors($this->validator->errors())
			->withInput();
		}

		$this->emailTemplate->create($input['name'], $input['subject'], $input['body']);
		
		return Redirect::route('emailTemplates.index')
		->with('alertSuccess', trans('fi.record_successfully_created'));
	}

	/**
	 * Display form for existing record
	 * @param  int $emailTemplateId
	 * @return \Illuminate\View\View
	 */
	public function edit($emailTemplateId)
	{
		$emailTemplate = $this->emailTemplate->find($emailTemplateId);
		
		return View::make('email_templates.form')
		->with(array('editMode' => true, 'emailTemplate' => $emailTemplate));
	}

	/**
	 * Validate and handle existing record form submission
	 * @param  int $emailTemplateId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($emailTemplateId)
	{
		$input = Input::all();

		if (!$this->validator->validate($input))
		{
			return Redirect::route('emailTemplates.edit', array($emailTemplateId))
			->with('editMode', true)
			->withErrors($this->validator->errors())
			->withInput();
		}

		$this->emailTemplate->update($emailTemplateId, $input['name'], $input['subject'], $input['body']);

		return Redirect::route('emailTemplates.index')
		->with('alertInfo', trans('fi.record_successfully_updated'));
	}

	/**
	 * Delete a record
	 * @param  int $emailTemplateId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($emailTemplateId)
	{
		$this->emailTemplate->delete($emailTemplateId);

		return Redirect::route('emailTemplates.index')
		->with('alert', trans('fi.record_successfully_deleted'));
	}

}