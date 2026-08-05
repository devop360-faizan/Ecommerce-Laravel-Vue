<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Address;

use App\DTOs\Address\AddressDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressRequest;
use App\Models\Address;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();
        return $this->success($addresses, 'Addresses retrieved successfully.');
    }

    public function store(AddressRequest $request): JsonResponse
    {
        $dto = AddressDTO::fromArray($request->validated());

        if ($dto->isDefault) {
            Address::where('user_id', $request->user()->id)->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => $request->user()->id,
            'label' => $dto->label,
            'full_name' => $dto->fullName,
            'phone' => $dto->phone,
            'address_line1' => $dto->addressLine1,
            'address_line2' => $dto->addressLine2,
            'city' => $dto->city,
            'state' => $dto->state,
            'country' => $dto->country,
            'postal_code' => $dto->postalCode,
            'is_default' => $dto->isDefault,
        ]);

        return $this->created($address, 'Address created successfully.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $address = Address::where('user_id', $request->user()->id)->find($id);
        
        if (!$address) {
            return $this->notFound('Address not found.');
        }

        $address->delete();

        return $this->noContent('Address deleted successfully.');
    }
}
