<?php

namespace common;
//namespace app\tool;
//bubble quick bucket radix select insert shell heap merge


use fastswoole\InstanceTrait;

class Sort
{
    use InstanceTrait;

    /**
     * 交换数组的值
     * @param $arr
     * @param $index1
     * @param $index2
     */
    public function array_swap(&$arr, $index1, $index2)
    {
        $temp = $arr[$index1];
        $arr[$index1] = $arr[$index2];
        $arr[$index2] = $temp;
    }

    /**
     * 合并数组
     * @param $arr1
     * @param $arr2
     *
     * @return mixed
     */
    public function array_union($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            $arr1[] = $value;
        }
        return $arr1;
    }

    /**
     * 创建随机数字数组
     * @param $num
     * @param $max
     *
     * @return array
     */
    public function createArr($num, $max)
    {
        $arr = [];
        for ($i = 0; $i < $num; $i++) {
            $arr[] = rand(0, $max);
        }
        return $arr;
    }

    /**
     * 打印数组
     * @param $arr
     * @param string $name
     *
     * @return bool
     */
    public function printArr($arr, $name = "")
    {
        if ($name) {
            echo "$name<br>";
        }
        foreach ($arr as $key => $value) {
            echo "$value,";
        }
        echo '<br>';
        return TRUE;
    }

    /**
     * 冒泡排序
     * @param $arr
     *
     * @return mixed
     */
    public function bubble(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 1; $i <= $end_index; $i++) {
            for ($j = 0; $j <= $end_index - $i; $j++) {
                if ($arr[$j + 1] < $arr[$j]) {
                    $this->array_swap($arr, $j, $j + 1);
                }
            }
        }
        return $arr;
    }

    /**
     * 快速排序获取支点
     * @param $arr
     * @param $left_index
     * @param $right_index
     *
     * @return int
     */
    protected function getPivot(&$arr, $left_index, $right_index)
    {
        $flag = $arr[$left_index];
        while ($left_index < $right_index) {
            while ($left_index < $right_index && $flag <= $arr[$right_index]) {
                $right_index--;
            }
            $this->array_swap($arr, $left_index, $right_index);
            while ($left_index < $right_index && $arr[$left_index] <= $flag) {
                $left_index++;
            }
            $this->array_swap($arr, $left_index, $right_index);
        }
        return $left_index;
    }

    /**
     * 快速排序
     * @param $arr
     * @param $left_index
     * @param $right_index
     *
     * @return mixed
     */
    public function quickSort(&$arr, $left_index, $right_index)
    {
        if ($right_index > $left_index) {
            $pivot = $this->getPivot($arr, $left_index, $right_index);
            $this->quickSort($arr, $left_index, $pivot - 1);
            $this->quickSort($arr, $pivot + 1, $right_index);
        }
        return $arr;
    }

    /**
     * 桶排序
     * @param $arr
     *
     * @return array|mixed
     */
    public function bucket(&$arr)
    {
        $max = max($arr);
        $min = min($arr);
        $bucket = array_fill($min, $max - $min + 1, []);
        foreach ($arr as $key => $value) {
            $bucket[$value][] = $value;
        }
        $arr = [];
        foreach ($bucket as $key => $value) {
            $arr = $this->array_union($arr, $value);
        }
        return $arr;
    }

    /**
     * 基排步进
     * @param $arr
     * @param $offset
     *
     * @return array|mixed
     */
    protected function radixSortStep(&$arr, $offset)
    {
        $bucket = array_fill(0, 10, []);
        foreach ($arr as $key => $value) {
            $num = strlen($value) < $offset ? 0 : substr($value, -$offset, 1);
            $bucket[$num][] = $value;
        }
        $arr = [];
        foreach ($bucket as $key => $value) {
            $arr = $this->array_union($arr, $value);
        }
        return $arr;
    }

    /**
     * 基排
     * @param $arr
     *
     * @return array|mixed
     */
    public function radixSort(&$arr)
    {
        $max_length = strlen(max($arr));
        for ($i = 1; $i <= $max_length; $i++) {
            $arr = $this->radixSortStep($arr, $i);
        }
        return $arr;
    }

    /**
     * 选择排序
     * @param $arr
     *
     * @return mixed
     */
    public function selectSort(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 0; $i <= $end_index; $i++) {
            $min_index = $i;
            for ($j = $i; $j <= $end_index; $j++) {
                if ($arr[$j] <= $arr[$min_index]) {
                    $min_index = $j;
                }
            }
            $this->array_swap($arr, $i, $min_index);
        }
        return $arr;
    }

    /**
     * 插入排序
     * @param $arr
     *
     * @return mixed
     */
    public function insertSort(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 0; $i <= $end_index; $i++) {
            for ($j = $i - 1; $j >= 0 && $arr[$j] > $arr[$j + 1]; $j--) {
                $this->array_swap($arr, $j, $j + 1);
            }
        }
        return $arr;
    }

    /**
     * 希尔排序
     * @param $arr
     *
     * @return mixed
     */
    public function shellSort(&$arr)
    {
        $count = count($arr);
        $end_index = $count - 1;
        for ($gap = intval($count / 2); $gap >= 1; $gap = intval($gap / 2)) {
            for ($i = $gap; $i <= $end_index; $i++) {
                for ($j = $i - $gap; $j >= 0 && $arr[$j] > $arr[$j + $gap]; $j -= $gap) {
                    $this->array_swap($arr, $j, $j + $gap);
                }
            }
        }
        return $arr;
    }

    /**
     * 变换堆
     * @param $arr
     * @param $top_index
     * @param $end_index
     *
     * @return mixed
     */
    protected function changeHeap(&$arr, $top_index, $end_index)
    {
        $largest_index = $top_index;
        $left_index = $top_index * 2 + 1;
        $right_index = $left_index + 1;
        if ($right_index <= $end_index && $arr[$right_index] > $arr[$largest_index]) {
            $largest_index = $right_index;
        }
        if ($left_index <= $end_index && $arr[$left_index] > $arr[$largest_index]) {
            $largest_index = $left_index;
        }
        if ($top_index != $largest_index) {
            $this->array_swap($arr, $top_index, $largest_index);
            $this->changeHeap($arr, $largest_index, $end_index);
        }
        return $arr;
    }

    /**
     * 堆排
    1. 数组生成堆(初始化)
    2. 堆(数组)头部和堆(数组)尾部互换
    3. 堆尺寸-1,重新生成堆
    4. 重复步骤2,直到堆的尺寸为 1
     * @param $arr
     *
     * @return mixed
     */
    public function heapSort(&$arr)
    {
        $count = count($arr);
        $end_index = $count - 1;
        for ($i = ceil($count / 2); $i >= 0; $i--) {
            $arr = $this->changeHeap($arr, $i, $end_index);
        }
        for ($i = $end_index; $i >= 0; $i--) {
            $this->array_swap($arr, 0, $i);
            $this->changeHeap($arr, 0, $i - 1);
        }
        return $arr;
    }

    /**
     * 合并排序步进
     * @param $left
     * @param $right
     *
     * @return mixed
     */
    protected function mergeSortStep(&$left, &$right)
    {
        $left_index = 0;
        $right_index = 0;
        $left_end_index = count($left) - 1;
        $right_end_index = count($right) - 1;
        $temp_arr = [];
        while ($left_index <= $left_end_index && $right_index <= $right_end_index) {
            if ($left[$left_index] > $right[$right_index]) {
                $temp_arr[] = $right[$right_index++];
            } else {
                $temp_arr[] = $left[$left_index++];
            }
        }
        $temp_arr = $this->array_union($temp_arr, array_slice($left, $left_index));
        $temp_arr = $this->array_union($temp_arr, array_slice($right, $right_index));
        return $temp_arr;
    }

    /**
     * 合并排序
     * @param $arr
     *
     * @return mixed
     */
    public function mergeSort($arr)
    {
        if (count($arr) <= 1) {
            return $arr;
        } else {
            $count = count($arr);
            $mid = intval($count / 2);
            $left = $this->mergeSort(array_slice($arr, 0, $mid));
            $right = $this->mergeSort(array_slice($arr, $mid));
            return $this->mergeSortStep($left, $right);
        }
    }

    public function run()
    {
        $sort_obj = new Sort();
        $arr = $sort_obj->createArr(50, 50);
        $sort_obj->printArr($arr, 'source');
        $temp_arr = $arr;
        $sort_obj->bubble($temp_arr);
        $sort_obj->printArr($temp_arr, 'bubble');
        $temp_arr = $arr;
        $sort_obj->quickSort($temp_arr, 0, 49);
        $sort_obj->printArr($temp_arr, 'quick');
        $temp_arr = $arr;
        $sort_obj->bucket($temp_arr);
        $sort_obj->printArr($temp_arr, 'bucket');
        $temp_arr = $arr;
        $sort_obj->radixSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'radix');
        $temp_arr = $arr;
        $sort_obj->selectSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'select');
        $temp_arr = $arr;
        $sort_obj->insertSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'insert');
        $temp_arr = $arr;
        $sort_obj->shellSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'shell');
        $temp_arr = $arr;
        $sort_obj->heapSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'heap');
        $temp_arr = $arr;
        $temp_arr = $sort_obj->mergeSort($temp_arr);
        $sort_obj->printArr($temp_arr, 'merge');
    }

}

