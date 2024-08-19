<?php

namespace App\Http\Services;

use App\Models\Guest;
use libphonenumber\PhoneNumberUtil;

class GuestService
{
    public function getCountryFromPhone(string $phoneNumber): ?string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($phoneNumber);
            return $phoneUtil->getRegionCodeForNumber($number);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setCountryIfNeeded(array $validated)
    {
        if (empty($validated['country'])) {
            $country = $this->getCountryFromPhone($validated['phone']);
            if ($country) {
                $validated['country'] = $country;
            } else {
                throw new \Exception('Unable to determine the country from the provided phone number. Please provide the country explicitly.');
            }
        }

        return $validated;
    }

    public function createGuest(array $data): Guest
    {
        $data = $this->setCountryIfNeeded($data);
        return Guest::create($data);
    }

    public function updateGuest(Guest $guest, array $data): Guest
    {
        $data = $this->setCountryIfNeeded($data);
        $guest->update($data);
        return $guest;
    }
}
