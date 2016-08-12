<?php namespace Biffy\Http\Requests\AccountExpense;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateAccountExpenseRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'amount' => 'numeric|required',
            'vendor_id' => 'integer|required',
            'file' => '',
            'comments' => 'required',
            'account_expense_category_id' => 'integer|required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}