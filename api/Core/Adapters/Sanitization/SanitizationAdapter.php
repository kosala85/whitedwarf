<?php

namespace Api\Core\Adapters\Sanitization;

class SanitizationAdapter
{
    public function sanitize($mixVariable)
    {
        if(is_array($mixVariable))
        {
            foreach($mixVariable as $key => $mixValue)
            {
                if(is_array($mixValue))
                {
                    $mixVariable[$key] = $this->sanitize($mixValue);
                }
                else
                {
                    $mixVariable[$key] = $this->clean($mixValue);
                }
            }
        }
        else
        {
            $mixVariable = $this->clean($mixVariable);
        }

        return $mixVariable;
    }


    private function clean($mixValue)
    {
        return strip_tags($mixValue);
    }
}