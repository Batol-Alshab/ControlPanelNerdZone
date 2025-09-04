<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Summery;
use App\Models\Video;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InquiryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Store a new inquiry in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeInquiry(Request $request)
    {
        try {
            $validated = $request->validate([
                'inquiry'          => 'required|String',
                'inquiryable_id'   => 'required|integer',
                'inquiryable_type' => ['required', 'string'],
            ]);

            $modelClass = match ($validated['inquiryable_type']) {
                'summery' => Summery::class,
                'video' => Video::class,
                default => null,
            };

            if (! $modelClass) {
                return $this->errorResponse('نوع غير مدعوم', 422);
            }
            $modelClass::findOrFail($validated['inquiryable_id']);
            $userId  = Auth::id();
            $inquiry = Inquiry::create([
                'inquiry'          => $validated['inquiry'],
                'user_id'          => $userId,
                'status'           => 'No Answer',
                'inquiryable_id'   => $validated['inquiryable_id'],
                'inquiryable_type' => $modelClass,
            ]);
            return $this->successResponse('تم الارسال بنجاح', ['inquiry' => $inquiry->makeHidden('user_id')]);
        } catch (ValidationException $e) {
            return $this->errorResponse('بيانات غير صالحة', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->notFound('العنصر المطلوب للاستفسار غير موجود', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ غير متوقع', 500);
        }
    }

    public function deleteInquiry(string $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $userId  = Auth::id();
        if ($inquiry->user_id !== $userId) {
            return $this->errorResponse('غير مصرح لك بحذف هذا الاستفسار', 403);
        }
        $inquiry->delete();
        return $this->successResponse('تم حذف الاستفسار بنجاح');
    }

    public function getVideoInquiries(string $id)
    {
        try {
            $video = Video::with(['inquiries' => function ($query) {
                $query->where('user_id', Auth::id())->with('answer');
            }])->findOrFail($id);
            $inquiries = $video->inquiries;
            return $this->successResponse([
                'inquiries' => $inquiries]);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('الفيديو غير موجود', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function getSummeryInquiries(string $id)
    {

        try {
            $summery = Summery::with(['inquiries' => function ($query) {
                $query->where('user_id', Auth::id())->with('answer');
            }])->findOrFail($id);
            $inquiries = $summery->inquiries;
            return $this->successResponse([
                'inquiries' => $inquiries]);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('الملخص غير موجود', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
