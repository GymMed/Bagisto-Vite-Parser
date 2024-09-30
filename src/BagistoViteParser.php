<?php

namespace GymMed\BagistoViteParser;

use Exception;

class BagistoViteParser
{
    public static function getDocumentPath(string $documentLocation, string $viterNamespace): string | Exception
    {
        $buildDirectory = self::getBuildDirectory($viterNamespace);
        $manifestPath = self::getManifestPath($viterNamespace);
        $manifestJson = json_decode(file_get_contents($manifestPath));

        return self::getPathFromManifest(
            $viterNamespace,
            $buildDirectory,
            $manifestJson,
            $documentLocation
        );
    }

    public static function getDocumentsPaths(array $documentsLocations, string $viterNamespace): array | Exception
    {
        $buildDirectory = self::getBuildDirectory($viterNamespace);
        $manifestPath = self::getManifestPath($viterNamespace);
        $manifestJson = json_decode(file_get_contents($manifestPath));

        $fullPaths = [];

        foreach ($documentsLocations as $documentLocation) {
            array_push($fullPaths, self::getPathFromManifest(
                $viterNamespace,
                $buildDirectory,
                $manifestJson,
                $documentLocation
            ));
        }

        return $fullPaths;
    }

    public static function getPathFromManifest(string &$viterNamespace, string &$buildDirectory, mixed &$manifestJson, string &$documentLocation)
    {
        if (!isset($manifestJson->$documentLocation)) {
            throw new Exception("Provided document location:\"" . $documentLocation . "\" doesn't exist in config/bagisto-vite in viter namespace:\"" . $viterNamespace . "\".");
        }

        return public_path($buildDirectory . '/' . $manifestJson->$documentLocation->file);
    }

    public static function getManifestPath(string $viterNamespace)
    {
        $buildDirectory = self::getBuildDirectory($viterNamespace);
        $manifestPath = public_path($buildDirectory) . '/manifest.json';

        if (!file_exists($manifestPath))
            throw new Exception("Provided viter namespace:'" . $viterNamespace .
                "' did not build manifest.json document yet! Run:\"npm run build\" in provided viter's package! Manifest path checking:" . $manifestPath);

        return $manifestPath;
    }

    public static function getBuildDirectory(string $viterNamespace)
    {
        if (!config('bagisto-vite.viters.' . $viterNamespace))
            throw new Exception("Provided viter namespace:\"" . $viterNamespace . "\" doesn't exist in config/bagisto-vite!");

        $buildDirectory = config('bagisto-vite.viters.' . $viterNamespace . '.build_directory');

        if (!$buildDirectory)
            throw new Exception("In config/bagisto-vite viter namespace:\"" . $viterNamespace . "\" doesn't contain a key for \"build_directory\"!");

        return $buildDirectory;
    }
}
