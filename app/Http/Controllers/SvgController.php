<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SvgController extends Controller
{
    public function index()
    {
        $filePath = public_path('svg/sprite.svg');

        if (!File::exists($filePath)) {
            return view('icons.index', ['icons' => [], 'message' => 'SVG sprite file not found!']);
        }

        $content = File::get($filePath);

        // Match all <symbol> IDs
        preg_match_all('/<symbol\s+id="([^"]+)"/', $content, $matches);
        $icons = $matches[1] ?? [];

        return view('icons.index', compact('icons'));
    }


    public function cleanup()
    {
        $filePath = public_path('svg/sprite.svg');

        if (!File::exists($filePath)) {
            return back()->with('error', 'SVG sprite file not found!');
        }

        $content = File::get($filePath);

        // Match all <symbol> blocks
        preg_match_all('/<symbol.*?<\/symbol>/s', $content, $matches);

        // Store all matched symbol blocks
        $symbols = $matches[0];
        $uniqueSymbols = [];
        $seenIds = [];

        foreach ($symbols as $symbol) {
            // Extract the ID from each symbol
            preg_match('/id="([^"]+)"/', $symbol, $idMatch);
            $id = $idMatch[1] ?? '';

            if ($id && !isset($seenIds[$id])) {
                $seenIds[$id] = true;
                $uniqueSymbols[$id] = $symbol;
            }
        }

        // Sort symbols by ID
        ksort($uniqueSymbols);

        // Wrap the cleaned and sorted symbols inside the <svg> tag
        $cleanedContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<svg xmlns=\"http://www.w3.org/2000/svg\" style=\"display: none;\">" . "\n";
        $cleanedContent .= implode("\n\n", $uniqueSymbols) . "\n</svg>";

        // Save the cleaned content back to the file
        File::put($filePath, $cleanedContent);

        return back()->with('success', 'Duplicate SVG symbols removed and sorted successfully!');
    }

    public function sortSvgSymbols()
    {
        $filePath = public_path('svg/sprite.svg');

        if (!File::exists($filePath)) {
            return back()->with('error', 'SVG sprite file not found!');
        }

        $content = File::get($filePath);

        // Match all <symbol> blocks, including comments before them
        preg_match_all('/<symbol.*?<\/symbol>/s', $content, $matches);

        // Store all matched symbol blocks
        $symbols = $matches[0];

        // Sort the symbols by their ID attribute
        usort($symbols, function($a, $b) {
            // Extract the ID from each symbol
            preg_match('/id="([^"]+)"/', $a, $idMatchA);
            preg_match('/id="([^"]+)"/', $b, $idMatchB);

            // Get the ID or an empty string if not found
            $idA = $idMatchA[1] ?? '';
            $idB = $idMatchB[1] ?? '';

            return strcmp($idA, $idB); // Sorting by ID in ascending order
        });

        // Wrap the sorted symbols inside the <svg> tag and add newlines between them
        $sortedContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<svg xmlns=\"http://www.w3.org/2000/svg\" style=\"display: none;\">" . "\n";
        $sortedContent .= implode("\n\n", $symbols) . "\n</svg>";

        // Save the sorted content back to the file
        File::put($filePath, $sortedContent);

        return back()->with('success', 'SVG symbols sorted successfully!');
    }




}
