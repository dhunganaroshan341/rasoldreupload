<?php

// app/Services/InputDataProcessor.php

namespace App\Services;

class InputDataProcessor
{
    protected $input;

    /**
     * Create a new InputDataProcessor instance.
     *
     * @param  string  $input
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Process the input and return two arrays: one for Client and one for OurServices.
     *
     * @return array
     */
    public function process()
    {
        // Normalize and split the input by different delimiters
        $normalizedInput = preg_replace('/[\s\-+\/]/', ',', $this->input);

        // Split the input into an array of values
        $values = array_map('trim', explode(',', $normalizedInput));

        // Assuming you want to split the values between two models
        // Example: First half for Client and second half for OurServices
        $half = ceil(count($values) / 2);
        $clientValues = array_slice($values, 0, $half);
        $ourServicesValues = array_slice($values, $half);

        return [
            'client' => $clientValues,
            'our_services' => $ourServicesValues,
        ];
    }
}
