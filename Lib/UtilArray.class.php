<?php
/**
 * UtilArray
 *
 *   ����:  ���� (panrui@discuz.com)
 *   ����ʱ��: 2008-03-20 13:40:23
 *   �޸ļ�¼:
 *
 *  $Id$
 */
class UtilArray {

    /**
     * �����ֶ�
     */
    private static $__sort_field;

    /**
     * ��������
     */
    private static $__sort_type;

    /**
     * ����Flag
     */
    private static $__sort_flag;

    /**
     * get_col ��ȡ��ά������ָ������
     *
     * @param  array  $data    ����Ϊ��ά����
     * @param  string $key_word ��Ҫ�еļ���
     * @param  string $key     �м���
     * @return array
     */
    public static function get_col($data, $keyword, $key = null) {
        if (!is_array($data)) {
            return false;
        }
        $result = array();
        if ($key && is_string($key)) {
            foreach ($data as $value) {
                $result[$value[$key]] = $value[$keyword];
            }
        } else {
            foreach ($data as $value) {
                $result[] = $value[$keyword];
            }
        }
        return $result;
    }

    /**
     * get_property
     * ��ȡ������ÿһ�����������
     *
     * @param  array $data �����ÿһ�����Ϊ����
     * @param  string $property �����������
     * @param  string $key ͬ����������
     * @return array
     */
    public static function get_property($data, $property, $key = null) {
        if (!is_array($data)) {
            return false;
        }
        $result = array();
        if ($key && is_string($key)) {
            foreach ($data as $object) {
                $result[$object->$key] = $object->$property;
            }
        } else {
            foreach ($data as $object) {
                $result[] = $object->$property;
            }
        }
        return $result;
    }

    /**
     * rebuild_by_col
     * ����ĳ���ֶΰѸ��ֶε�ֵ�������KEY��������
     * ���� $a = array(
     *                 array('uId' => '1', 'data' => 'test'),
     *                 array('uId' => '2', 'data' => 'test2')
     *                )
     * UtilArray::rebuild_by_col($a, 'uId');
     * array(
     *       '1' => array('uId' => '1', 'data' => 'test'),
     *       '2' => array('uId' => '2', 'data' => 'test2')
     *      )
     *
     * @param  array $data ��ά����
     * @param  string $keyword �ֶ���
     * @return array
     */
    public static function rebuild_by_col($data, $keyword) {

        // ������ԭ������
        if (!$data) {
            return $data;
        }

        $result = array();

        foreach ($data as $value) {
            $result[$value[$keyword]] = $value;
        }

        return $result;
    }

    /**
     * rebuild_by_property
     * ���������ж����������������
     * �÷�������rebuild_by_col
     *
     * @param  array $data ��������
     * @param  string $property �����������
     * @return array
     */
    public static function rebuild_by_property($data, $property) {

        // ������ԭ������
        if (!$data) {
            return $data;
        }

        $result = array();

        foreach ($data as $object) {
            $result[$object->$property] = $object;
        }

        return $result;
    }

    /**
     * ��keys��ֵΪ�±�, ��value��ֵΪֵ����ϳ��µ�����
     *
     * ���磺
     *      $a = array(
     *              array('uId' => 'b', 'name' => 'aa'),
     *              array('uId' => 'cc', 'name' => 'dd')
     *              );
     *      $b = array('a' => 'b', 'cc' => 'd');
     *
     * UtilArray::combine($a, $b, 'uId', true, false);
     *          ���� array(
     *                  'd' => array('uId' => 'cc', 'name' => 'dd')
     *                  );
     * UtilArray::combine($a, $b, 'uId', false, false);
     *          ���� array(
     *                  'b' => array('uId' => 'b', 'name' => 'aa');
     *                  );
     *
     * @param array $values ֵ����
     * @param array $keys ������ ������һά����,�Ҳ������ظ�ֵ
     * @param string $field ��$values�����е�$field�ֶ�Ϊ�Ƚ��ֶ�
     * @param boolean $cmp_with_key ��$keys�ļ���ֵΪ�Ƚ϶���
     * @param boolean $key_as_index ��$keys�ļ���ֵΪ����ֵ������
     * @return array
     */
    public static function combine($values, $keys, $field, $cmp_with_key = true, $key_as_index = false) {
        $result = array();
        if (!$keys || !$values || !$field) {
            return $result;
        }

        if (!$cmp_with_key) {
            // ����ǱȽ�ֵ�Ļ� ��תһ������, �������Ƚ�
            $keys = array_flip($keys);
            // ����ֵҲҪ��ת
            $key_as_index = !$key_as_index;
        }

        foreach ($values as $k => $value) {
            // ���ָ���ֶ��ڲ���$value��
            if (!array_key_exists($field, $value)) {
                continue;
            }

            // ȡ��value��ָ���ֶε�ֵ
            if (!($idx = $value[$field])) {
                continue;
            }

            // ������keys�������Ƿ����
            if (array_key_exists($idx, $keys)) {
                if ($key_as_index) {
                    $result[$idx] = $value;
                } else {
                    $result[$keys[$idx]] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * merge_by_key ��ĳһָ���н��кϲ�
     *
     * ������Լ�� $data2 �� $data1 ����ָ���У�������һ�Զ�Ĺ�ϵҲ����һ��һ�Ĺ�ϵ
     *            �������Ƕ�Զ�Ĺ�ϵ��
     *            ʹ�ñ�������ע�����˳��,$data2��ָ���е�ֵ��û���ظ�����ע�⡣
     *
     * @param  mixed $data
     * @param  mixed $data2
     * @param  mixed $key
     * @return void
     */
    public static function merge_by_key($data, $data2, $keyword, $keyword2 = null) {
        if (is_array($data) && is_array($data2)) {
            $result = array();
            $data_map = array();
            // index
            $tmp_keyword = $keyword;
            if ($keyword2) {
                $tmp_keyword = $keyword2;
            }
            foreach ($data2 as $value) {
                $data_map[$value[$tmp_keyword]] = $value;
            }
            foreach ($data as $value) {
                if (is_array($data_map[$value[$keyword]])) {
                    $result[] = array_merge($value, $data_map[$value[$keyword]]);
                } else {
                    $result[] = $value;
                }
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * build_array_query
     * ��������HTTP QUERY����
     *
     * @param  mixed $data
     * @param  string $key
     * @return void
     */
    public static function build_array_query($data, $key = '') {

        $query = $data;

        if ($key) {
            $query = array($key => $data);
        }

        return http_build_query($query, '', '&');
    }

    /**
     * list_cols
     * �������л�ȡ�ض���һ��ֵ
     * (˵��: ���صĸ��������е�ֵû��ȥ��, ������Ҫ�����д���)
     *
     * @param array $array Դ���� array(
     *                                  array('uId' => 1, 'gId'=>2, 'tId' => null),
     *                                  array('uId' => 2, 'gId'=>3, 'tId' => null),
     *                                  array('uId' => 3, 'gId'=>4, 'tId' => null),
     *                                  array('uId' => 4, 'gId'=>5, 'tId' => null),
     *                                  ....
     *                              );
     * @param array $keywords ��Ҫ��ȡ���ݵļ� array('uId', 'gId', 'tId');
     * @return array(
     *              'uId' => array(1, 2, 3, 4, 5, ...),
     *              'gId' => array(2, 3, 4, 5, ...),
     *              'tId' => array(),
     *          );
     */
    public static function list_cols($data, $keywords = array()) {

        $return_data = array();

        if (!$keywords) {
            return $return_data;
        }

        // ������������
        foreach ($keywords as $keyword) {
            $return_data[$keyword] = array();
        }

        // ���Ѿ����ɵ�������������ֵ
        foreach ($data as $col) {
            foreach ($col as $key => $value) {
                if (in_array($key, $keywords) && $value) {
                    $return_data[$key][] = $value;
                }
            }
        }

        return $return_data;
    }

    /**
     * sort_by_field
     * �Զ�ά���飬��ָ�����ֶ�ֵ����
     * �ر�������MySQL select IN ��������ָ���ֶ�����
     *
     * ���� $data = array(
     *                    array('uId' => '9', 'data' => 'test1'),
     *                    array('uId' => '3', 'data' => 'test2'),
     *                    array('uId' => '2', 'data' => 'test3'),
     *                    array('uId' => '5', 'data' => 'test4'),
     *                   )
     *
     * UtilArray::sort_by_field($data, 'uId', 'ASC', 'NATURAL');
     *
     *
     * return array(
     *              array('uId' => '2', 'data' => 'test3'),
     *              array('uId' => '3', 'data' => 'test2'),
     *              array('uId' => '5', 'data' => 'test4'),
     *              array('uId' => '9', 'data' => 'test1')
     *             )
     *
     * @param  array $data ��ά��������
     * @param  string $sort_field �����ֶ���
     * @param  string $sort_type ASC, DESC �������ͣ�������
     * @param  string $sort_flag REGULAR, NUMERIC, STRING, NATURAL �Ƚ����ͣ�ͨ���Ƚϡ����ֱȽϡ��ַ����Ƚϡ���Ȼ�Ƚϣ��μ�php sort����
     * @return array
     */
    public static function sort_by_field($data, $sort_field, $sort_type = 'ASC', $sort_flag = 'REGULAR') {

        if (!$data || !is_array($data)) {
            return false;
        }

        if (!$sort_field) {
            return false;
        }

        $sort_type = strtoupper($sort_type);
        switch ($sort_type) {
        case 'DESC':
            break;
        case 'ASC':
        default:
            $sort_type = 'ASC';
        }

        $sort_flag = strtoupper($sort_flag);
        switch ($sort_flag) {
        case 'NUMERIC':
            break;
        case 'STRING':
            break;
        case 'NATURAL':
            break;
        case 'REGULAR':
        default:
            $sort_flag = 'REGULAR';
        }

        self::$__sort_field = $sort_field;
        self::$__sort_type = $sort_type;
        self::$__sort_flag = $sort_flag;

        usort($data, array(self, '__sort_by_field'));

        // ����
        self::$__sort_field = '';
        self::$__sort_type = '';
        self::$__sort_flag = '';

        return $data;
    }

    /**
     * __sort_by_field
     * usort�ص����Զ��尴�е�����ʽ
     *
     * @param  array $row1 ��һ����¼
     * @param  array $row2 �ڶ�����¼
     * @return integer row1 < row2 ����-1��row1 = row2 ����0��row1 > row2 ����1�����������෴
     */
    private function __sort_by_field($row1, $row2) {

        $sort_field = self::$__sort_field;
        $sort_type = self::$__sort_type;
        $sort_flag = self::$__sort_flag;

        // Ĭ����Ϊ�����������
        $res = 0;

        if ($sort_flag == 'REGULAR') {
            if ($row1[$sort_field] < $row2[$sort_field]) {
                $res = -1;
            } elseif ($row1[$sort_field] > $row2[$sort_field]) {
                $res = 1;
            }
        } elseif ($sort_flag == 'NUMERIC') {
            $res = bccomp($row1[$sort_field], $row2[$sort_field]);
        } elseif ($sort_flag == 'STRING') {
            $res = strcmp($row1[$sort_field], $row2[$sort_field]);
        } elseif ($sort_flag == 'NATURAL') {
            $res = strnatcmp($row1[$sort_field], $row2[$sort_field]);
        }

        if ($sort_type == 'DESC') {
            $res *= -1;
        }

        return $res;
    }

    /**
     * sort_by_value
     * �Զ�ά���飬��ָ����sort�����ֵ����
     * �ر�������MySQL select IN ��������ָ��KEY��˳������
     *
     * �ر����ѣ�sortValue�������ֶ�ֵ���������ظ������ݳ���
     * ���� data ���ݱ� sortValue �٣������ܷ�֮
     *
     * ���� $data = array(
     *                    array('uId' => '9', 'data' => 'test1'),
     *                    array('uId' => '3', 'data' => 'test2')
     *                    array('uId' => '2', 'data' => 'test3')
     *                    array('uId' => '5', 'data' => 'test4')
     *                   )
     *      $sort_value = array('2', '5', '3', '9');
     *
     * UtilArray::sort_by_value($data, 'uId', $sort_value);
     *
     *
     * return array(
     *              array('uId' => '2', 'data' => 'test3'),
     *              array('uId' => '5', 'data' => 'test4')
     *              array('uId' => '3', 'data' => 'test2')
     *              array('uId' => '9', 'data' => 'test1')
     *             )
     *
     * @param  array $data ����
     * @param  string $sort_field �����ֶ���
     * @param  array $sort_value �����˳��
     * @return array
     */
    public static function sort_by_value($data, $sort_field, $sort_value) {

        if (!$data || !is_array($data)) {
            return false;
        }

        if (!$sort_field || !$sort_value || !is_array($sort_value)) {
            return false;
        }

        // ��ת����key=>value���ڲ���
        $sort_flip = array_flip($sort_value);

        // ����������
        $sorted = array();

        foreach ($data as $value) {
            if ($value && isset($value[$sort_field]) && isset($sort_flip[$value[$sort_field]])) {
                $index = $sort_flip[$value[$sort_field]];

                $sorted[$index] = $value;
            }
        }

        if ($sorted) {
            ksort($sorted, SORT_NUMERIC);
        }

        return $sorted;
    }

    /**
     * merge
     * �ϲ����飬ȥ�أ����˿�ֵ��intval
     *
     * notice: �˷�����ȥ�����й���key�������Ҫ����key����ʹ�ô˷���
     *
     * ���磺
     * UtilArray::merge($array1, $array2, $array3, $array4);
     * UtilArray::merge($array1, $array2, $array3, $array4, true);
     * UtilArray::merge($array1, $array2, $array3, $array4, false);
     *
     * array $array1 [, array $array2, array $array3 ... ] [$intval]
     * @param  array $array1, $array2 �����Ҫmerge������
     * @param  boolean $intval �Ƿ��Զ����������
     * @return array
     */
    public static function merge() {

        // Ĭ����Ҫintval����
        $intval = true;

        // ��ȡ���в���
        $function_args = func_get_args();

        if (!$function_args) {
            return array();
        }

        // ȡ�����һ������
        $last_arg = end($function_args);

        if ($last_arg === true || $last_arg === false) {
            // ����$intval����
            array_pop($function_args);

            $intval = $last_arg;
        }

        foreach ($function_args as $key => $arg) {
            // ����ÿ����������֤ÿ��������Ϊ����
            if (is_string($arg)) {
                $arg = array(trim($arg));
            } elseif (is_numeric($arg)) {
                $arg = array($arg);
            } elseif (is_array($arg)) {
                $arg = $arg;
            } else {
                $arg = array();
            }

            $function_args[$key] = $arg;
        }

        // �ϲ�
        $result = call_user_func_array('array_merge', $function_args);

        // ���ͻ�����
        if ($intval) {
            $result = array_map('intval', $result);
        }

        // ���˿�ֵ
        $result = array_filter($result);

        // ȥ��
        $result = array_unique($result);

        // ��������
        if ($intval) {
            sort($result, SORT_NUMERIC);
        } else {
            sort($result);
        }

        return $result;
    }

    /**
     * ����������(��ָ��key��)���ݽ��з�ҳ
     * @param  array $data          ��������
     * @param  array & $page_options ��ҳ����
     * @return array                ��ҳ�ָ�����������
     */
    public static function page($data, & $page_options) {

        if (!$data || !is_array($data)) {
            return $data;
        }

        if ($page_options && !$page_options['total_items']) {
            $page_options['total_items'] = count($data);
        }
        $page_options = Pager::resolve_options($page_options);

        if ($page_options) {
            return array_slice($data, $page_options['start'], $page_options['per_page']);
        } else {
            return $data;
        }

    }


    /**
     * filter_by_keyword
     * �����鰴�ؼ��ֽ��й���
     * @param  array $data          ��������
     * @param  array $field         �ֶ�
     * @param  array $keyword           �ؼ���
     * @return array                ��ҳ�ָ�����������
     */
    public static function filter_by_keyword($data, $field, $keyword) {
        if (!$data || !is_array($data)) {
            return array();
        }

        if (!$field || !is_string($field)) {
            return $data;
        }

        if (!$keyword || !is_string($keyword)) {
            return $data;
        }

        $result = array();

        foreach ($data as $value) {
            if (!$value || !is_array($value)) {
                continue;
            }
            if (!$value[$field] || !is_string($value[$field])) {
                continue;
            }
            if (strpos($value[$field], $keyword) === false) {
                continue;
            }

            $result[] = $value;
        }

        return $result;
    }

    /**
     * search_by_keyword
     * ���������ָ���ؼ��ֲ��Ҳ���ҳ
     *
     *    ʾ��:
     *    $data = array(
     *                  array('cId' => 100016, 'uId' => 105124, 'uName' => 'aaa', 'uCreated' => '2013-07-04 11:22:33'),
     *                  array('cId' => 100016, 'uId' => 105135, 'uName' => 'bab', 'uCreated' => '2013-07-02 11:22:33'),
     *                  array('cId' => 100016, 'uId' => 105147, 'uName' => 'ccc', 'uCreated' => '2013-07-01 11:22:33'),
     *                  array('cId' => 100016, 'uId' => 105159, 'uName' => 'ddd', 'uCreated' => '2013-07-03 11:22:33'),
     *                  array('cId' => 100016, 'uId' => 105161, 'uName' => 'eee', 'uCreated' => '2013-07-05 11:22:33'),
     *                 );
     *    $page_options = array('page' => 1, 'per_page' => 5);
     *    $result = UtilArray::search_by_keyword($data, 'uName', 'aa', $page_options, 'uCreated', 'DESC');
     *
     * @param  array $data          ��������
     * @param  array $search_field   ��ѯ�ֶ�
     * @param  array $search_keyword     ��ѯ�ؼ���
     * @param  array & $page_options ��ҳ����
     * @param  array $sort_field     �����ֶ�
     * @param  array $sort_type      ����ʽ ASC/DESC
     * @param  array $sort_flag      ����ģʽ
     * @return array                ��ҳ�ָ�����������
     */
    public static function search_by_keyword($data, $search_field, $search_keyword, &$page_options = array(), $sort_field = '', $sort_type = 'ASC', $sort_flag = 'REGULAR') {

        $result = array();
        if (!$data || !is_array($data)) {
            return $result;
        }

        // ����
        $result = self::filter_by_keyword($data, $search_field, $search_keyword);
        if (!$result || !is_array($result)) {
            return $result;
        }

        // ����
        if ($sort_field) {
            $result = self::sort_by_field($result, $sort_field, $sort_type, $sort_flag);
            if (!$result || !is_array($result)) {
                return $result;
            }
        }

        // ��ҳ
        $result = self::page($result, $page_options);
        return $result;
    }

    /**
     * filter_by_conditions
     * ��������ɸѡ����
     *
     * @param  array $data        ԭʼ����
     * @param  array $conditions  ɸѡ����
     * @return array              ���ؽ��
     *
     * ʾ��:
     *    $data = array(
     *        array('user_id'=> 101, 'name' => 'halo', 'age' => 19, 'skills' => 'c,php,java,c++'),
     *        array('user_id'=> 102, 'name' => 'check', 'age' => 18, 'skills' => 'php'),
     *        array('user_id'=> 103, 'name' => 'joshua', 'age' => 12, 'skills' => 'php,java,c++'),
     *        array('user_id'=> 104, 'name' => 'jimmy', 'age' => 29, 'skills' => 'c,c++'),
     *        array('user_id'=> 105, 'name' => 'cony', 'age' => 49, 'skills' => 'c,c++'),
     *        array('user_id'=> 106, 'name' => 'monkey', 'age' => 13, 'skills' => 'net,php,java,c++'),
     *        array('user_id'=> 107, 'name' => 'wilson', 'age' => 82, 'skills' => 'sql,java,'),
     *        array('user_id'=> 108, 'name' => '', 'age' => 82, 'skills' => 'sql,java,'),
     *        array('user_id'=> 109, 'name' => 'nnn', 'age' => 82, 'skills' => ''),
     *    );
     *
     *    $conditions = array(
     *        array('user_id', '!=', 105),
     *        array('name', 'like', 'y'),
     *        array('skills', 'in', array('c', 'd')),
     *    );
     *
     *    $result = UtilArray::filter_by_conditions($data, $conditions);
     *
     */
    public static function filter_by_conditions($data, $conditions) {

        $result = array();
        if (!$data || !is_array($data)) {
            return $result;
        }

        if (!$conditions || !is_array($conditions)) {
            return $data;
        }

        foreach ($data as $key => $row) {
            if ($row = self::__filter_row_by_conditions($row, $conditions)) {
                $result[$key] = $row;
            }
        }

        return $result;

    }

    /**
     * __filter_row_by_conditions
     *
     * @param  array $row
     * @param  array $conditions
     * @return mixed
     */
    private static function __filter_row_by_conditions($row, $conditions) {
        if (!$row || !is_array($row)) {
            return false;
        }

        foreach ($conditions as $condition) {
            list($field, $method, $value) = $condition;

            switch ($method) {
            case '>':
            case '<':
            case '=':
            case '>=':
            case '<=':
            case '!=':
                if (!self::__filter_row_compare($row[$field], $method, $value)) {
                    return false;
                }
                break;
            case 'like':
                if (strpos($row[$field], $value) === false) {
                    return false;
                }
                break;
            case 'between':
                if (array_key_exists('from', $row[$field])) {
                    $compare_value_from = $row[$field]['from'];
                    $compare_value_to = $row[$field]['to'];
                } else {
                    $compare_value_from = $row[$field];
                    $compare_value_to = $row[$field];
                }

                if ($compare_value_from > $value['to']) {
                    return false;
                }

                if ($compare_value_to < $value['from']) {
                    return false;
                }
                break;
            case 'find_in_set':
                if (!$row[$field]) {
                    return false;
                }

                if (!array_intersect($value, explode(',', $row[$field]))) {
                    return false;
                }

                break;
            default:
                break;
            }
        }

        return $row;
    }

    /**
     * __filter_row_compare
     *
     * @param  integer $from_value
     * @param  string $compare
     * @param  integer $to_value
     * @return boolean
     */
    private static function __filter_row_compare($from_value, $compare, $to_value) {

        switch ($compare) {
        case '>':
            return $from_value > $to_value;
        case '<':
            return $from_value < $to_value;
        case '=':
            return $from_value == $to_value;
        case '>=':
            return $from_value >= $to_value;
        case '<=':
            return $from_value <= $to_value;
        case '!=':
            return $from_value != $to_value;
        default :
            return false;
        }

        return false;
    }

    /**
     * identical_values
     * �Ƚ����������Ƿ���ȫ���
     *
     * <example>
     * $array1 = array("red", "green", "blue");
     * $array2 = array("green", "red", "blue");
     * $array3 = array("red", "green", "blue", "yellow");
     * $array4 = array("red", "yellow", "blue");
     * $array5 = array("x" => "red", "y" => "green", "z" => "blue");
     *
     * identical_values($array1, $array2);  // true
     * identical_values($array1, $array3);  // false
     * identical_values($array1, $array4);  // false
     * identical_values($array1, $array5);  // true
     * </example>
     *
     * @param  array $array1
     * @param  array $array2
     * @return boolean
     */
    public static function identical_values($array1, $array2) {

        if (count($array1) != count($array2)) {
            return false;
        }

        sort($array1);
        sort($array2);

        return $array1 == $array2;
    }
}

/**
 * excel ����
 *
 * @param string $filename �ļ���
 * @param array $title ����(һά����)
 * @param array $list �б�(��ά����)
 */
function excel_out($filename,$titles,$list)
{
    $file_type = "vnd.ms-excel";
    $file_ending = "xls";
    header("Content-Type: application/$file_type;charset=utf-8");
    header("Content-Disposition: attachment; filename=$filename.$file_ending");
    header("Pragma: no-cache");
    header("Expires: 0");

    $len = count($titles);
    for($i=0; $i<$len; $i++)
    {
        echo iconv('utf-8','gb2312',$titles[$i])."\t";
        if($i+1 == $len) echo "\n";
    }

    $len = count($list);
    for($i=0; $i<$len; $i++)
    {
        if($i == 0) $keys = array_keys($list[0]);
        for($k=0; $k<count($list[$i]); $k++)
        {
            echo iconv('utf-8','gb2312',$list[$i]["{$keys[$k]}"])."\t";
            if($k+1 == count($list[$i])) echo "\n";
        }
    }
}

