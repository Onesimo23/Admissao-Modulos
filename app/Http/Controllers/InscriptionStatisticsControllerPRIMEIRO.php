<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InscriptionStatisticsController extends Controller
{
    public function getCounts()
    {
        $counts = [
            // Global statistics
            'all_candidates' => Candidate::count(),
            'all_payments' => Payment::count(),
            'pending_payments' => Payment::where('status', 0)->count(),
            'confirmed_payments' => Payment::where('status', 1)->count(),

            // Candidates by Course
            'candidates_by_course' => Candidate::select('course_id', DB::raw('count(*) as total'))
                ->with('course:id,name')
                ->groupBy('course_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->course->name => [
                        'total' => $item->total,
                        'pending' => Candidate::where('course_id', $item->course_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 0);
                            })->count(),
                        'confirmed' => Candidate::where('course_id', $item->course_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 1);
                            })->count(),
                    ]];
                })->sortKeys(),

            // Candidates by University
            'candidates_by_university' => Candidate::select('university_id', DB::raw('count(*) as total'))
                ->with('university:id,name')
                ->groupBy('university_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->university->name => [
                        'total' => $item->total,
                        'pending' => Candidate::where('university_id', $item->university_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 0);
                            })->count(),
                        'confirmed' => Candidate::where('university_id', $item->university_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 1);
                            })->count(),
                    ]];
                })->sortKeys(),

            // Candidates by Regime
            'candidates_by_regime' => Candidate::select('regime_id', DB::raw('count(*) as total'))
                ->with('regime:id,name')
                ->groupBy('regime_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->regime->name => [
                        'total' => $item->total,
                        'pending' => Candidate::where('regime_id', $item->regime_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 0);
                            })->count(),
                        'confirmed' => Candidate::where('regime_id', $item->regime_id)
                            ->whereHas('payment', function($q) {
                                $q->where('status', 1);
                            })->count(),
                    ]];
                })->sortKeys(),

            // Detailed statistics by University and Course
            'candidates_by_university_course' => Candidate::with(['university:id,name', 'course:id,name'])
                ->get()
                ->groupBy(function ($item) {
                    return $item->university->name . '|' . $item->course->name;
                })
                ->map(function ($items, $key) {
                    [$university, $course] = explode('|', $key);
                    return [
                        'university' => $university,
                        'course' => $course,
                        'total' => $items->count(),
                        'pending' => $items->filter(function ($item) {
                            return $item->payment && $item->payment->status == 0;
                        })->count(),
                        'confirmed' => $items->filter(function ($item) {
                            return $item->payment && $item->payment->status == 1;
                        })->count(),
                    ];
                })
                ->values()
                ->groupBy('university')
                ->sortKeys(),
        ];

        return $counts;
    }
}