<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Material;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{use ApiResponseTrait;
    public function getLessonProgress(int $lessonId)
    {
        $user             = Auth::user();
        $lesson           = Lesson::findOrFail($lessonId);
        $allTests         = $lesson->tests->pluck('id');
        $countTest        = $lesson->tests->pluck('id')->count();
        $completedTestIds = $user->tests()->pluck('test_id');
        $commonIds        = $allTests->intersect($completedTestIds)->count();
        if ($countTest > 0) {
            $precentag = ($commonIds / $countTest) * 100;
            return response()->json([
                'lesson_id'             => $lesson->id,
                'lesson_name'           => $lesson->name,
                'progress_percentage'   => round($precentag, 2),
                'completed_tests_count' => $commonIds,
                'total_tests_count'     => $countTest,
            ]);
        } else {

            return $this->errorResponse('لايوجد اختبارات لهذا الدرس');
        }

    }

    public function getMaterialProgress(int $MaterialId)
    {
        // 1. استرجاع المادة مع جميع دروسها واختباراتها
        $material = Material::with('lessons.tests')->findOrFail($MaterialId);
        $user     = Auth::user();
        // 2. حساب إجمالي عدد الاختبارات للمادة بأكملها
        $totalTestsCount = 0;
        foreach ($material->lessons as $lesson) {
            // foreach($lesson->tests as $test)
            $totalTestsCount += $lesson->tests->count();
        }

        // 3. استرجاع معرفات جميع الاختبارات التي أنجزها المستخدم
        $completedTestIds = $user->tests()->pluck('test_id');

        // 4. حساب عدد الاختبارات المكتملة للمادة بأكملها
        $completedTestsCount = 0;
        foreach ($material->lessons as $lesson) {
            // فلترة اختبارات كل درس بناءً على المعرفات المكتملة
            $completedTestsCount += $lesson->tests->whereIn('id', $completedTestIds)->count();
        }
        // return $completedTestsCount;

        // 5. حساب النسبة المئوية
        $progressPercentage = 0;
        if ($totalTestsCount > 0) {
            $progressPercentage = ($completedTestsCount / $totalTestsCount) * 100;
        }

        // 6. إرجاع النتيجة
        return response()->json([
            'material_id'           => $material->id,
            'material_name'         => $material->name,
            'progress_percentage'   => round($progressPercentage, 2),
            'completed_tests_count' => $completedTestsCount,
            'total_tests_count'     => $totalTestsCount,
        ]);
    }

    public function getAllMaterialsProgress()
    {
        $user = Auth::user();
        // $materials        = Material::with('lessons.tests')->get();
        $materials = Material::where('section_id', $user->section_id)->with('lessons.tests')->get();

        $completedTestIds = $user->tests()->pluck('test_id');

        $allMaterialsProgress = [];
        foreach ($materials as $material) {
            // حساب إجمالي عدد الاختبارات للمادة الحالية
            $totalTestsCount = 0;
            foreach ($material->lessons as $lesson) {
                $totalTestsCount += $lesson->tests->count();
            }

            // حساب عدد الاختبارات المكتملة للمادة الحالية
            $completedTestsCount = 0;
            foreach ($material->lessons as $lesson) {
                $completedTestsCount += $lesson->tests->whereIn('id', $completedTestIds)->count();
            }

            // حساب النسبة المئوية
            $progressPercentage = 0;
            if ($totalTestsCount > 0) {
                $progressPercentage = ($completedTestsCount / $totalTestsCount) * 100;
            }

            // إضافة نتيجة المادة الحالية إلى المصفوفة
            $allMaterialsProgress[] = [
                'material_id'           => $material->id,
                'material_name'         => $material->name,
                'progress_percentage'   => round($progressPercentage, 2),
                'completed_tests_count' => $completedTestsCount,
                'total_tests_count'     => $totalTestsCount,
            ];
        }

        // إرجاع مصفوفة تحتوي على تقدم كل مادة
        return response()->json($allMaterialsProgress);
    }
    public function getMterialDetails($materialId)
    {$user = Auth::user();
        $allLessonDetails                = [];
        $materail                        = Material::findOrFail($materialId);
        $lessons                         = $materail->lessons()->get();
        foreach ($lessons as $lesson) {
            $allTests         = $lesson->tests->pluck('id');
            $countTest        = $lesson->tests->pluck('id')->count();
            $completedTestIds = $user->tests()->pluck('test_id');
            $commonIds        = $allTests->intersect($completedTestIds)->count();
            if ($countTest > 0) {
                $precentag          = ($commonIds / $countTest) * 100;
                $allLessonDetails[] = [
                    'lesson_id'             => $lesson->id,
                    'lesson_name'           => $lesson->name,
                    'progress_percentage'   => round($precentag, 2),
                    'completed_tests_count' => $commonIds,
                    'total_tests_count'     => $countTest,
                ];
            } else {
                return $this->errorResponse('لايوجد اختبارات لهذا الدرس');
            }
        }
        return $allLessonDetails;}

    public function getMterialRate()
    {$user = Auth::user();
        $allMaterialRate                 = [];
        $materails                       = Material::where('section_id', $user->section_id)->get();
        foreach ($materails as $material) {
            $materailRate = $user->materials()->where('Material_id', $material->id)->pluck('rate');

            $allMaterialRate[] = [
                'material_name' => $material->name,
                'rate'          => $materailRate,
            ];

        }
        return $allMaterialRate;}}
