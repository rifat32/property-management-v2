<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenancyAgreementCreateRequest;
use App\Http\Requests\TenancyAgreementUpdateRequest;
use App\Http\Utils\ErrorUtil;
use App\Http\Utils\UserActivityUtil;
use App\Models\Business;
use App\Models\TenancyAgreement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenancyAgreementController extends Controller
{
    use ErrorUtil, UserActivityUtil;


    /**
     * @OA\Post(
     *      path="/v1.0/tenancy-agreement",
     *      operationId="createTenancyAgreement",
     *      tags={"property_management.property_agreement"},
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      summary="Store property agreement",
     *      description="This method is to store a new property agreement, replacing any existing agreement for the same property and landlord.",
  * @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *         required={},
 *         @OA\Property(property="agreed_rent", type="string", example="1000.00"),
 *         @OA\Property(property="security_deposit_hold", type="string", example="2000.00"),
 *         @OA\Property(property="tenant_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
 *         @OA\Property(property="rent_payment_option", type="string", enum={"By_Cash", "By_Cheque", "Bank_Transfer"}, example="By_Cash"),
 *         @OA\Property(property="tenant_contact_duration", type="string", example="12 months"),
 *         @OA\Property(property="date_of_moving", type="string", format="date", example="2024-11-01"),
 *         @OA\Property(property="let_only_agreement_expired_date", type="string", format="date", example="2025-11-01", nullable=true),
 *         @OA\Property(property="tenant_contact_expired_date", type="string", format="date", example="2025-11-01", nullable=true),
 *         @OA\Property(property="rent_due_date", type="string", format="date", example="2024-11-05"),
 *         @OA\Property(property="no_of_occupants", type="string", example="3"),
 *         @OA\Property(property="renewal_fee", type="string", example="50.00"),
 *         @OA\Property(property="housing_act", type="string", example="Housing Act 1988"),
 *         @OA\Property(property="let_type", type="string", example="Standard Let"),
 *         @OA\Property(property="terms_and_conditions", type="string", example="Updated terms and conditions..."),
 *         @OA\Property(property="agency_name", type="string", example="XYZ Realty"),
 *         @OA\Property(property="landlord_name", type="string", example="John Doe"),
 *         @OA\Property(property="agency_witness_name", type="string", example="Jane Smith"),
 *         @OA\Property(property="tenant_witness_name", type="string", example="Mark Johnson"),
 *         @OA\Property(property="agency_witness_address", type="string", example="123 Agency St, City, Country"),
 *         @OA\Property(property="tenant_witness_address", type="string", example="456 Tenant Rd, City, Country"),
 *         @OA\Property(property="guarantor_name", type="string", example="Sarah Lee", nullable=true),
 *         @OA\Property(property="guarantor_address", type="string", example="789 Guarantor Ave, City, Country", nullable=true)
 *     )
 * )
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(),
     *      )
     * )
     */

    public function createTenancyAgreement(TenancyAgreementCreateRequest $request)
    {
        try {
            $this->storeActivity($request, "");
            return DB::transaction(function () use ($request) {
                $request_data = $request->validated();
                $agreement = TenancyAgreement::create($request_data);
                $agreement->tenants()->sync($request_data["tenant_ids"]);
                return response($agreement, 201);
            });
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }


    /**
     * @OA\Put(
     *      path="/v1.0/tenancy-agreement",
     *      operationId="updateTenancyAgreement",
     *      tags={"property_management.property_agreement"},
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      summary="Update property agreement",
     *      description="This method updates an existing property agreement based on the provided data.",
  * @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *         required={},
 *         @OA\Property(property="id", type="string", example="1"),
 *         @OA\Property(property="agreed_rent", type="string", example="1000.00"),
 *         @OA\Property(property="security_deposit_hold", type="string", example="2000.00"),
 *         @OA\Property(property="tenant_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
 *         @OA\Property(property="rent_payment_option", type="string", enum={"By_Cash", "By_Cheque", "Bank_Transfer"}, example="By_Cash"),
 *         @OA\Property(property="tenant_contact_duration", type="string", example="12 months"),
 *         @OA\Property(property="date_of_moving", type="string", format="date", example="2024-11-01"),
 *         @OA\Property(property="let_only_agreement_expired_date", type="string", format="date", example="2025-11-01", nullable=true),
 *         @OA\Property(property="tenant_contact_expired_date", type="string", format="date", example="2025-11-01", nullable=true),
 *         @OA\Property(property="rent_due_date", type="string", format="date", example="2024-11-05"),
 *         @OA\Property(property="no_of_occupants", type="string", example="3"),
 *         @OA\Property(property="renewal_fee", type="string", example="50.00"),
 *         @OA\Property(property="housing_act", type="string", example="Housing Act 1988"),
 *         @OA\Property(property="let_type", type="string", example="Standard Let"),
 *         @OA\Property(property="terms_and_conditions", type="string", example="Updated terms and conditions..."),
 *         @OA\Property(property="agency_name", type="string", example="XYZ Realty"),
 *         @OA\Property(property="landlord_name", type="string", example="John Doe"),
 *         @OA\Property(property="agency_witness_name", type="string", example="Jane Smith"),
 *         @OA\Property(property="tenant_witness_name", type="string", example="Mark Johnson"),
 *         @OA\Property(property="agency_witness_address", type="string", example="123 Agency St, City, Country"),
 *         @OA\Property(property="tenant_witness_address", type="string", example="456 Tenant Rd, City, Country"),
 *         @OA\Property(property="guarantor_name", type="string", example="Sarah Lee", nullable=true),
 *         @OA\Property(property="guarantor_address", type="string", example="789 Guarantor Ave, City, Country", nullable=true)
 *     )
 * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(),
     *      ),
     * )
     */

    public function updateTenancyAgreement(TenancyAgreementUpdateRequest $request)
    {
        try {

            $this->storeActivity($request, "");
            return DB::transaction(function () use ($request) {
                $request_data = $request->validated();

                // Find the agreement, including soft-deleted records
                $agreement = TenancyAgreement::
                whereHas('property', function ($q) {
                    // Ensure the property is created by the authenticated user
                    $q->where('properties.created_by', auth()->user()->id);
                })
                ->where('id', $request_data['id'])
                    ->first();  // Returns null if not found

                // Check if agreement exists
                if (!$agreement) {
                    return response()->json(['message' => 'Agreement not found'], 404);
                }
                // Ensure agreement exists

                $agreementData = collect($request_data)->only([
                    "property_id",
                    'agreed_rent',
                    'security_deposit_hold',
                    'rent_payment_option',
                    'tenant_contact_duration',
                    'date_of_moving',
                    'let_only_agreement_expired_date',
                    'tenant_contact_expired_date',
                    'rent_due_date',
                    'no_of_occupants',
                    'renewal_fee',
                    'housing_act',
                    'let_type',
                    'terms_and_conditions',
                    'agency_name',
                    'landlord_name',
                    'agency_witness_name',
                    'tenant_witness_name',
                    'agency_witness_address',
                    'tenant_witness_address',
                    'guarantor_name',
                    'guarantor_address',
                ]);

                // Fill the model with the mass-assignable attributes
                $agreement->fill($agreementData->toArray());
                $agreement->save(); // Save the agreement

                // Handle tenant_ids separately and sync them with the agreement
                $agreement->tenants()->sync($request_data["tenant_ids"]);

                return response($agreement, 200);
            });
        } catch (Exception $e) {
            return $this->sendError($e, 500, $request);
        }
    }





    /**
     * @OA\Get(
     *      path="/v1.0/tenancy-agreements",
     *      operationId="getTenancyAgreements",
     *      tags={"property_management.property_agreement"},
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      summary="Get property agreements",
     *      description="This method retrieves the history of property agreements for a given property and landlord, including soft-deleted agreements.",
     *      @OA\Parameter(
     *          name="tenant_ids",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="property_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(),
     *      )
     * )
     */

    public function getTenancyAgreements(Request $request)
    {
        try {
            // Start building the query for history
            $query = TenancyAgreement::whereHas('property', function ($q) {
                // Ensure the property is created by the authenticated user
                $q->where('properties.created_by', auth()->user()->id);
            })
            ->when($request->filled('tenant_ids'), function ($q) use ($request) {
                // Filter by property_id if provided
                $q->whereHas('tenants', function ($q) {
                    $tenant_ids = explode(',', request()->input('tenant_ids'));
                    // Ensure the property is created by the authenticated user
                    $q->whereIn('tenants.id', $tenant_ids);
                });
            })
                ->when($request->filled('property_id'), function ($q) use ($request) {
                    // Filter by property_id if provided
                    $q->where('property_id', $request->property_id);
                })
                ->when($request->filled('id'), function ($q) use ($request) {
                    // If specific ID is provided, return that record only
                    return $q->where('id', $request->input('id'))->first();
                }, function ($q) {
                    // Otherwise, fetch history (including soft-deleted agreements)
                    return $q
                        // ->withTrashed() // Include soft-deleted records
                        ->when(!empty($request->per_page), function ($q) {
                            // Paginate if per_page is provided
                            return $q->paginate(request()->per_page);
                        }, function ($q) {
                            // Otherwise, return all agreements
                            return $q->get();
                        });
                });

            return response()->json($query, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500, $request);
        }
    }



    /**
     * @OA\Delete(
     *      path="/v1.0/tenancy-agreements/{agreement_id}",
     *      operationId="deleteTenancyAgreement",
     *      tags={"property_management.property_agreement"},
     *       security={
     *           {"bearerAuth": {}},
     *           {"pin": {}}
     *       },
     *      summary="Delete a document from a property",
     *      description="This method deletes a document associated with a specific property",
     *      @OA\Parameter(
     *          name="property_id",
     *          in="path",
     *          required=true,
     *          description="Property ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="document_id",
     *          in="path",
     *          required=true,
     *          description="Document ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Document deleted successfully",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Property or Document Not Found",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(),
     *      )
     * )
     */

    public function deleteTenancyAgreement($agreement_id)
    {
        try {

            $business = Business::where([
                "owner_id" => request()->user()->id
            ])->first();

            if (!$business) {
                return response()->json([
                    "message" => "you don't have a valid business"
                ], 401);
            }

            if (!($business->pin == request()->header("pin"))) {
                return response()->json([
                    "message" => "invalid pin"
                ], 401);
            }

            // Find the property
            $property_agreement = TenancyAgreement::
            whereHas('property', function ($q) {
                // Ensure the property is created by the authenticated user
                $q->where('properties.created_by', auth()->user()->id);
            })
            ->where([
                "id" => $agreement_id
            ])
            ->first();
            if (!$property_agreement) {
                return response()->json([
                    "message" => "no agreement found"
                ], 404);
            }

            // Delete the document
            $property_agreement->delete();


            return response()->json(['message' => 'agreement deleted successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
}