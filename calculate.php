<?php

class Pattern {
    public $subtract_syllable_patterns = array (
                                          "cia(l|$)",
                                          "tia",
                                          "cius",
                                          "cious",
                                          "[^aeiou]giu",
                                          "[aeiouy][^aeiouy]ion",
                                          "iou",
                                          "sia$",
                                          "eous$",
                                          "[oa]gue$",
                                          ".[^aeiuoycgltdb]{2,}ed$",
                                          ".ely$",
                                          "^jua",
                                          "uai",
                                          "eau",
                                          "[aeiouy](b|c|ch|d|dg|f|g|gh|gn|k|l|ll|lv|m|mm|n|nc|ng|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y|z)e$",
                                          "[aeiouy](b|c|ch|dg|f|g|gh|gn|k|l|lch|ll|lv|m|mm|n|nc|ng|nch|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|th|v|y|z)ed$",
                                          "[aeiouy](b|ch|d|f|gh|gn|k|l|lch|ll|lv|m|mm|n|nch|nn|p|r|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y)es$",
                                          "^busi$",
                                        );
}
class Readability
{
    function ease_score($text)
    {
        # Calculate score
        $asl = $this->average_words_sentence($text);
        $asw = $this->average_syllables_word($text);

        # Return of 0.0 to 100.0
        return round(206.835 - (1.015 * $asl) - (84.6 * $asw));
    }

    function average_words_sentence($text)
    {
        $sentences = strlen(preg_replace('/[^\.!?]/', '', $text));
        $words     = strlen(preg_replace('/[^ ]/', '', $text));
        return ($words / $sentences);
    }

    function average_syllables_word($text)
    {
        $syllables = 0;
        $words     = explode(' ', $text);
        for ($i = 0; $i < count($words); $i++) {
            $syllables = $syllables + $this->count_syllables($words[$i]);
        }
        return ($syllables / count($words));
    }

    function count_syllables($word)
    {

        $pattern = new Pattern();
        $subtract_syllable_patterns = $pattern->{'subtract_syllable_patterns'};

        $valid_word_parts = array();

        $word       = preg_replace('/[^a-z]/is', '', strtolower($word));
        $word_parts = preg_split('/[^aeiouy]+/', $word);
        foreach ($word_parts as $key => $value) {
            if ($value <> '') {
                $valid_word_parts[] = $value;
            }
        }

        $syllables = 0;

        foreach ($subtract_syllable_patterns as $syl) {
            $syllables -= preg_match('~' . $syl . '~', $word);
        }

        if (strlen($word) == 1) {
            $syllables++;
        }
        $syllables += count($valid_word_parts);
        $syllables = ($syllables == 0) ? 1 : $syllables;
        return $syllables;
    }
}


$readability = new Readability();

$test_sentence = "Heavy metals are generally defined as metals with relatively high densities, atomic weights, or atomic numbers. The criteria used, and whether metalloids are included, vary depending on the author and context. In metallurgy, for example, a heavy metal may be defined on the basis of density, whereas in physics the distinguishing criterion might be atomic number, while a chemist would likely be more concerned with chemical behavior. More specific definitions have been published, but none of these have been widely accepted. The definitions surveyed in this article encompass up to 96 out of the 118 chemical elements; only mercury, lead and bismuth meet all of them.";

echo $readability->ease_score($test_sentence);
echo "\n";

$pattern = new Pattern();
echo "Printing size of the first of four pattern arrays: ";
echo sizeof($pattern->{'subtract_syllable_patterns'});

# What PHP version is this?
echo "\n";
echo 'Current PHP version: ' . phpversion();
?>