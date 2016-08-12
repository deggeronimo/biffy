<?php namespace Biffy\Http\Requests\AccountExpense;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateAccountExpenseRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'amount' => 'numeric',
            'vendor_id' => 'integer',
            'comments' => '',
            'account_expense_category_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}