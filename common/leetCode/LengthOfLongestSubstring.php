<?php
class LengthOfLongestSubstring
{

    /**
     * @param String $s
     * @return Integer
     */
    public function lengthOfLongestSubstring($s)
    {
        $sub_arr = [];
        $count = 0;
        $arr = str_split($s);
        foreach ($arr as $key => $value) {
            if (in_array($value, $sub_arr, true)) {
                $pos = array_search($value, $sub_arr);
                $sub_arr = array_splice($sub_arr, $pos + 1);
                $sub_arr[] = $value;
            } else {
                if (is_string($value) && !empty($value)) {
                    $sub_arr[] = $value;
                }
                else if (is_numeric($value)) {
                    $sub_arr[] = $value;
                }
            }
            $count = max($count, count($sub_arr));
        }
        return $count;
    }

    public function demo($s)
    {
        $res = $this->lengthOfLongestSubstring($s);
//        var_dump($res);
        return $res;
    }

}
