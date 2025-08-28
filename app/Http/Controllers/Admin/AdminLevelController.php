<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Stage;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Stage $stage)
    {
        $levels = $stage->levels()->orderBy('index')->get();
        return view('admin.levels.index', compact('stage', 'levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Stage $stage)
    {
        return view('admin.levels.create', compact('stage'));
    }

    /**
     * Store a newly created resource in storage.
     */
     
    public function store(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'index' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:drag_drop,multiple_choice,tf1,match_pairs,flip_cards,reorder',
            'pass_score' => 'required|integer|min:0|max:100',
            'instructions' => 'nullable|string',
        ]);

        // Process content based on level type
        $content = [];
        
        if ($validated['type'] === 'drag_drop') {
            $categories = [];
            $categoriesInput = $request->input('content.categories', []);
            
            foreach ($categoriesInput as $category) {
                if (!empty($category['name']) && !empty($category['items'])) {
                    $items = array_filter(array_map('trim', explode("\n", $category['items'])));
                    if (!empty($items)) {
                        $categories[$category['name']] = array_values($items);
                    }
                }
            }
            
            $content['categories'] = $categories;
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 300);
            $content['max_hints'] = (int)$request->input('content.max_hints', 4);
            
        } elseif ($validated['type'] === 'multiple_choice') {
            $questions = [];
            $questionsInput = $request->input('content.questions', []);
            
            foreach ($questionsInput as $question) {
                if (!empty($question['question']) && !empty($question['options'])) {
                    $options = array_filter(array_map('trim', explode("\n", $question['options'])));
                    if (!empty($options) && isset($question['correct_answer']) && isset($question['explanation'])) {
                        $questions[] = [
                            'question' => $question['question'],
                            'options' => array_values($options),
                            'correct_answer' => (int)$question['correct_answer'],
                            'explanation' => $question['explanation']
                        ];
                    }
                }
            }
            
            $content['questions'] = $questions;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 180);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'tf1') {
            $questions = [];
            $questionsInput = $request->input('content.questions', []);
            
            foreach ($questionsInput as $question) {
                if (!empty($question['code']) && !empty($question['statement']) && 
                    isset($question['answer']) && !empty($question['explanation'])) {
                    $questions[] = [
                        'code' => $question['code'],
                        'statement' => $question['statement'],
                        'answer' => filter_var($question['answer'], FILTER_VALIDATE_BOOLEAN),
                        'explanation' => $question['explanation']
                    ];
                }
            }
            
            $content['questions'] = $questions;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'match_pairs') {
            $pairs = [];
            $pairsInput = $request->input('content.pairs', []);
            
            foreach ($pairsInput as $pair) {
                if (!empty($pair['left']) && !empty($pair['right'])) {
                    $pairs[] = [
                        'left' => $pair['left'],
                        'right' => $pair['right']
                    ];
                }
            }
            
            $sequences = [];
            $sequencesInput = $request->input('content.sequences', []);
            
            foreach ($sequencesInput as $sequence) {
                if (!empty($sequence['title']) && !empty($sequence['steps']) && !empty($sequence['correct_order'])) {
                    $steps = array_filter(array_map('trim', explode("\n", $sequence['steps'])));
                    $correctOrder = array_map('intval', explode(',', $sequence['correct_order']));
                    
                    if (!empty($steps) && !empty($correctOrder)) {
                        $sequences[] = [
                            'title' => $sequence['title'],
                            'steps' => array_values($steps),
                            'correct_order' => $correctOrder
                        ];
                    }
                }
            }
            
            $content['pairs'] = $pairs;
            $content['sequences'] = $sequences;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'flip_cards') {
            $cards = [];
            $cardsInput = $request->input('content.cards', []);
            
            foreach ($cardsInput as $card) {
                if (!empty($card['front']) && !empty($card['back'])) {
                    $cards[] = [
                        'front' => $card['front'],
                        'back' => $card['back']
                    ];
                }
            }
            
            $content['cards'] = $cards;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 300);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'reorder') {
            // Process lines of code
            $linesText = $request->input('content.lines', '');
            $lines = array_filter(array_map('trim', explode("\n", $linesText)));
            
            if (!empty($lines)) {
                $content['lines'] = array_values($lines);
            } else {
                $content['lines'] = [];
            }
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 4);
        }

        // Create the level
        $level = $stage->levels()->create([
            'index' => $validated['index'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'pass_score' => $validated['pass_score'],
            'instructions' => $validated['instructions'],
            'content' => $content,
        ]);

        return redirect()->route('admin.stages.levels.index', $stage)
            ->with('success', 'Level created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stage $stage, Level $level)
    {
        return view('admin.levels.show', compact('stage', 'level'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stage $stage, Level $level)
    {
        return view('admin.levels.edit', compact('stage', 'level'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Stage $stage, Level $level)
{
    $validated = $request->validate([
        'index' => 'required|integer|min:1',
        'title' => 'required|string|max:255',
        'type' => 'required|string|in:drag_drop,multiple_choice,tf1,match_pairs,flip_cards,reorder',
        'pass_score' => 'required|integer|min:0|max:100',
        'instructions' => 'nullable|string',
    ]);

    $oldIndex = $level->index;
    $newIndex = $validated['index'];

    // Use a transaction to ensure data integrity
    return \DB::transaction(function () use ($request, $stage, $level, $validated, $oldIndex, $newIndex) {
        // First, let's update all the level content except the index
        $content = [];
        
        if ($validated['type'] === 'drag_drop') {
            $categories = [];
            $categoriesInput = $request->input('content.categories', []);
            
            foreach ($categoriesInput as $category) {
                if (!empty($category['name']) && !empty($category['items'])) {
                    $items = array_filter(array_map('trim', explode("\n", $category['items'])));
                    if (!empty($items)) {
                        $categories[$category['name']] = array_values($items);
                    }
                }
            }
            
            $content['categories'] = $categories;
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 300);
            $content['max_hints'] = (int)$request->input('content.max_hints', 4);
            
        } elseif ($validated['type'] === 'multiple_choice') {
            $questions = [];
            $questionsInput = $request->input('content.questions', []);
            
            foreach ($questionsInput as $question) {
                if (!empty($question['question']) && !empty($question['options'])) {
                    $options = array_filter(array_map('trim', explode("\n", $question['options'])));
                    if (!empty($options) && isset($question['correct_answer']) && isset($question['explanation'])) {
                        $questions[] = [
                            'question' => $question['question'],
                            'options' => array_values($options),
                            'correct_answer' => (int)$question['correct_answer'],
                            'explanation' => $question['explanation']
                        ];
                    }
                }
            }
            
            $content['questions'] = $questions;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 180);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'tf1') {
            $questions = [];
            $questionsInput = $request->input('content.questions', []);
            
            foreach ($questionsInput as $question) {
                if (!empty($question['code']) && !empty($question['statement']) && 
                    isset($question['answer']) && !empty($question['explanation'])) {
                    $questions[] = [
                        'code' => $question['code'],
                        'statement' => $question['statement'],
                        'answer' => filter_var($question['answer'], FILTER_VALIDATE_BOOLEAN),
                        'explanation' => $question['explanation']
                    ];
                }
            }
            
            $content['questions'] = $questions;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'match_pairs') {
            $pairs = [];
            $pairsInput = $request->input('content.pairs', []);
            
            foreach ($pairsInput as $pair) {
                if (!empty($pair['left']) && !empty($pair['right'])) {
                    $pairs[] = [
                        'left' => $pair['left'],
                        'right' => $pair['right']
                    ];
                }
            }
            
            $sequences = [];
            $sequencesInput = $request->input('content.sequences', []);
            
            foreach ($sequencesInput as $sequence) {
                if (!empty($sequence['title']) && !empty($sequence['steps']) && !empty($sequence['correct_order'])) {
                    $steps = array_filter(array_map('trim', explode("\n", $sequence['steps'])));
                    $correctOrder = array_map('intval', explode(',', $sequence['correct_order']));
                    
                    if (!empty($steps) && !empty($correctOrder)) {
                        $sequences[] = [
                            'title' => $sequence['title'],
                            'steps' => array_values($steps),
                            'correct_order' => $correctOrder
                        ];
                    }
                }
            }
            
            $content['pairs'] = $pairs;
            $content['sequences'] = $sequences;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'flip_cards') {
            $cards = [];
            $cardsInput = $request->input('content.cards', []);
            
            foreach ($cardsInput as $card) {
                if (!empty($card['front']) && !empty($card['back'])) {
                    $cards[] = [
                        'front' => $card['front'],
                        'back' => $card['back']
                    ];
                }
            }
            
            $content['cards'] = $cards;
            $content['intro'] = $request->input('content.intro', '');
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 300);
            $content['max_hints'] = (int)$request->input('content.max_hints', 3);
            
        } elseif ($validated['type'] === 'reorder') {
            // Process lines of code
            $linesText = $request->input('content.lines', '');
            $lines = array_filter(array_map('trim', explode("\n", $linesText)));
            
            if (!empty($lines)) {
                $content['lines'] = array_values($lines);
            } else {
                $content['lines'] = [];
            }
            
            // Process hints
            $hintsText = $request->input('content.hints', '');
            $content['hints'] = array_filter(array_map('trim', explode("\n", $hintsText)));
            
            // Set other fields
            $content['time_limit'] = (int)$request->input('content.time_limit', 240);
            $content['max_hints'] = (int)$request->input('content.max_hints', 4);
        }

        // Update the level content first (without changing the index yet)
        $level->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'pass_score' => $validated['pass_score'],
            'instructions' => $validated['instructions'],
            'content' => $content,
        ]);

        // If the index has changed, handle the reordering
        if ($oldIndex != $newIndex) {
            // Get all levels in this stage, ordered by index
            $allLevels = $stage->levels()->orderBy('index')->get();
            
            // Create a new ordered array without the current level
            $newOrder = [];
            foreach ($allLevels as $lvl) {
                if ($lvl->id != $level->id) {
                    $newOrder[] = $lvl;
                }
            }
            
            // Insert the level at the new position (adjusting for 0-based index)
            array_splice($newOrder, $newIndex - 1, 0, [$level]);
            
            // Use large positive indices temporarily to avoid unique constraint issues
            $maxIndex = $allLevels->count() + 1000; // Use a large offset
            foreach ($allLevels as $lvl) {
                $lvl->update(['index' => $maxIndex + $lvl->index]);
            }
            
            // Now set the correct indices
            $index = 1;
            foreach ($newOrder as $lvl) {
                $lvl->update(['index' => $index]);
                $index++;
            }
        }

        return redirect()->route('admin.stages.levels.index', $stage)
            ->with('success', 'Level updated successfully.');
    });
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stage $stage, Level $level)
    {
        $level->delete();
        return redirect()->route('admin.stages.levels.index', $stage)
            ->with('success', 'Level deleted successfully.');
    }
}