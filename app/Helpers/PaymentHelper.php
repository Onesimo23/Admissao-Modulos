<?php

namespace App\Helpers;

class PaymentHelper
{
    /**
     * Calculate the check digit for a bank reference.
     *
     * @param string $entity - Bank entity code.
     * @param string $reference - Generated reference number.
     * @param int $amount - Payment amount.
     * @return int - The generated check digit.
     */
    
	public function calculateCheckDigit(string $entity, string $reference, int $amount): int
    {
        // Remove whitespace and concatenate the entity, reference, and amount
        $str = trim($entity) . trim($reference) . trim($amount);
        
        // Add four zeros at the end and calculate the check digit using modulo 97
        $checkDigit = 98 - bcmod(($str . "0000"), 97);

        return $checkDigit;
    }

    /**
     * Generate the complete bank reference with the check digit.
     *
     * @param string $entity - Bank entity code.
     * @param string $baseReference - Partial reference number.
     * @param string $month - Month related to the reference.
     * @param int $amount - Payment amount.
     * @return string - Complete bank reference with check digit.
     */
    public function generateCompleteReference(string $entity, string $baseReference, string $year, int $amount): string
    {
        // Concatenate the base reference with the month
        $partialReference = trim($baseReference) . trim($year);

        // Calculate the check digit using the calculateCheckDigit method
        $checkDigit = $this->calculateCheckDigit($entity, $partialReference, $amount);

        // Ensure the check digit is two digits long
        $formattedCheckDigit = str_pad($checkDigit, 2, '0', STR_PAD_LEFT);

        // Return the complete reference (partialReference + formattedCheckDigit)
        return $partialReference . trim($formattedCheckDigit);
    }
}