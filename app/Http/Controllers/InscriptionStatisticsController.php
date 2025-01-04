<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Payment;
use App\Models\Province;  // Adicione esta linha
use App\Models\Course;   // Adicione esta linha
use App\Models\University;
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

            // Candidates by University and Regime
            'candidates_by_university_regime' => Candidate::with(['university:id,name', 'regime:id,name'])
                ->get()
                ->groupBy(function ($item) {
                    return $item->university->name . '|' . $item->regime->name;
                })
                ->map(function ($items, $key) {
                    [$university, $regime] = explode('|', $key);
                    return [
                        'university' => $university,
                        'regime' => $regime,
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

            // Detailed statistics by University, Regime and Course
            //detailed_statistics' => Candidate::with(['university:id,name', 'regime:id,name', 'course:id,name'])
            //   ->get()
            //   ->groupBy(function ($item) {
            //       return $item->university->name . '|' . $item->regime->name . '|' . $item->course->name;
            //   })
            //   ->map(function ($items, $key) {
            //       [$university, $regime, $course] = explode('|', $key);
            //       return [
            //           'university' => $university,
            //           'regime' => $regime,
            //           'course' => $course,
            //           'total' => $items->count(),
            //           'pending' => $items->filter(function ($item) {
            //               return $item->payment && $item->payment->status == 0;
            //           })->count(),
            //           'confirmed' => $items->filter(function ($item) {
            //               return $item->payment && $item->payment->status == 1;
            //           })->count(),
            //       ];
            //   })
            //   ->values()
            //   ->groupBy(function ($item) {
            //       return $item['university'] . '|' . $item['regime'];
            //   })
            //   ->map(function ($items) {
            //       return [
            //           'university' => $items->first()['university'],
            //           'regime' => $items->first()['regime'],
            //           'courses' => $items->values(),
            //           'total' => $items->sum('total'),
            //           'pending' => $items->sum('pending'),
            //           'confirmed' => $items->sum('confirmed'),
            //       ];
            //   })
            //   ->sortBy('university')
            //   ->values(),
				
			// Detailed statistics by University, Regime and Course
			'detailed_statistics' => Candidate::with(['university:id,name', 'regime:id,name', 'course:id,name'])
				->get()
				->groupBy(function ($item) {
					return $item->university->name . '|' . $item->regime->name . '|' . $item->course->name;
				})
				->map(function ($items, $key) {
					[$university, $regime, $course] = explode('|', $key);
					return [
						'university' => $university,
						'regime' => $regime,
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
				->groupBy(function ($item) {
					return $item['university'] . '|' . $item['regime'];
				})
				->map(function ($items) {
					return [
						'university' => $items->first()['university'],
						'regime' => $items->first()['regime'],
						'courses' => $items->values()
						->sortByDesc('confirmed') // Ordena cada grupo pelos confirmados
						->sortByDesc('pending')    // Depois, pelos pendentes
						->values(),
						'total' => $items->sum('total'),
						'pending' => $items->sum('pending'),
						'confirmed' => $items->sum('confirmed'),
					];
				})
				->sortByDesc('confirmed')  // Ordena grupo maior por confirmados
				->sortByDesc('pending')    // E por pendentes
				->sortBy('university')
				->values(),				
				

            // Summary by University
            'university_summary' => Candidate::with('university:id,name')
                ->get()
                ->groupBy('university.name')
                ->map(function ($items) {
                    return [
                        'total' => $items->count(),
                        'pending' => $items->filter(function ($item) {
                            return $item->payment && $item->payment->status == 0;
                        })->count(),
                        'confirmed' => $items->filter(function ($item) {
                            return $item->payment && $item->payment->status == 1;
                        })->count(),
                    ];
                })->sortKeys(),
				
            // Summary by Regime
			'regime_summary' => Candidate::with('regime:id,name')
				->get()
				->groupBy('regime.name')
				->map(function ($items) {
					return [
						'total' => $items->count(),
						'pending' => $items->filter(function ($item) {
							return $item->payment && $item->payment->status == 0;
						})->count(),
						'confirmed' => $items->filter(function ($item) {
							return $item->payment && $item->payment->status == 1;
						})->count(),
					];
				})->sortKeys()			
        ];

        return $counts;
    }
	
	public function getCandidatesByLocalExamAndCourse()
	{
		$report = Candidate::with(['course', 'province'])
			->where('regime_id', 1)
			->whereHas('payment', function ($query) {
				$query->where('status', 1);
			})
			->get()
			->groupBy(function ($candidate) {
				return $candidate->local_exam . '|' . $candidate->course_id;
			})
			->map(function ($candidates, $key) {
				[$localExamId, $courseId] = explode('|', $key);
				
				$localExam = Province::findOrFail($localExamId);
				$course = Course::findOrFail($courseId);
				
				return [
					'local_exam' 		=> $localExam->name,
					'course' 			=> $course->name,
					'total_candidates' 	=> $candidates->count(),
					'candidate_id'     => $candidates->sortBy('name')->pluck('id')->values()->toArray(),
					'candidate_names'  => $candidates
						->sortBy(fn($candidate) => $candidate->name . ' ' . $candidate->surname)
						->map(fn($candidate) => $candidate->name . ' ' . $candidate->surname)
						->values()
						->toArray(),					
					//'candidate_names' => $candidates->pluck('name')->sort()->values()->toArray() // Ordena os nomes
				];
			})
			->sortBy('local_exam')
			->groupBy('local_exam')
			->map(function ($localExamData) {
				return $localExamData->sortByDesc('total_candidates');
			});

		return $report;
	}


	public function getCandidatesByLocalExamAndCourseAndUniversity()
	{
		$report = Candidate::with(['course', 'province', 'university'])
			->where('regime_id', 1)
			->whereHas('payment', function ($query) {
				$query->where('status', 1);
			})
			->get()
			->groupBy(function ($candidate) {
				return $candidate->local_exam . '|' . $candidate->course_id;
			})
			->map(function ($candidates, $key) {
				[$localExamId, $courseId] = explode('|', $key);
				
				$localExam = Province::findOrFail($localExamId);
				$course = Course::findOrFail($courseId);
	
				return [
					'local_exam' 		    => $localExam->name,
					'course' 			    => $course->name,
					'total_candidates' 	    => $candidates->count(),
					'candidate_id'          => $candidates->sortBy('name')->pluck('id')->values()->toArray(),
					'candidate_names'       => $candidates
						->sortBy(fn($candidate) => $candidate->name . ' ' . $candidate->surname)
						->map(fn($candidate) => $candidate->name . ' ' . $candidate->surname)
						->values()
						->toArray(),
					'candidate_university' => $candidates->sortBy('name')->map(function($candidate) {
							switch ($candidate->university_id) {
								case 1:
									return 'Chongoene';
								case 2:
									return 'Maxixe';
								case 3:
									return 'Massinga';
								case 4:
									return 'Manhiça';
								default:
									return 'Não definido';
							}
						})->values()->toArray(),
				];
			})
			->sortBy('local_exam')
			->groupBy('local_exam')
			->map(function ($localExamData) {
				return $localExamData->sortByDesc('total_candidates');
			});
		return $report;
	}


	public function getCandidatesByLocalExamAndCourseTotal()
	{
		$report = Candidate::with(['province'])
			->where('regime_id', 1)
			->whereHas('payment', function ($query) {
				$query->where('status', 1);
			})
			->get()
			->groupBy('province_id')
			->map(function ($candidates, $provinceId) {
				$province = Province::findOrFail($provinceId);
				
				return [
					'local_exam'        => $province->name,
					'total_candidates'  => $candidates->count(),
				];
			})
			->sortByDesc('total_candidates');

		return $report;
	}	
}