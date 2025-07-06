class StoreCardRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('generate_new_card');
    }

    public function rules()
    {
        return [
            'package_id' => 'required|exists:packages,id',
            'pos_id' => 'required|exists:points_of_sale,id'
        ];
    }
}