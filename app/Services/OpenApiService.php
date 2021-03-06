<?php

namespace App\Services;

use Carbon\Carbon;
use League\Uri;

/**
 * Class OpenApiService
 * @package App\Services
 */
class OpenApiService
{
    /** @var ?string */
    private $api_key = null;
    /**
     * @var \Illuminate\Config\Repository|mixed|null
     */
    private $api_nongsaro_key = null;

    /** @var string  */
    private $api_call_url = 'http://211.237.50.150:7080';
    /**
     * @var string
     */
    private $api_call_url_nongsaro = 'http://api.nongsaro.go.kr';

    /**
     * @var string
     */
    private $api_call_url_naver_blog_rss = 'https://rss.blog.naver.com';

    /**
     * OpenApiService constructor.
     */
    public function __construct()
    {
        $this->api_key = config('open_api.key');
        $this->api_nongsaro_key = config('open_api.api_nongsaro_key');
    }

    /**
     *
     */
    const API_PAGE_LIMIT = 100;
    /**
     *
     */
    const API_GRID_MACHINES = "Grid_20141119000000000080_1"; //  농기계 현황
    /**
     *
     */
    const API_GRID_DICTIONARY = "Grid_20151230000000000339_1"; // 농업용어
    /**
     *
     */
    const API_GRID_SPECIAL_CROPS = "Grid_20141119000000000065_1"; // 특용작물생산통계
    /**
     *
     */
    const API_GRID_EMPTY_HOUSES = "Grid_20150914000000000230_1"; // 농촌 빈집 정보

    /**
     *
     */
    const API_NONGSARO_EDUCATION_FARMS_LISTS = "fmlgEdcFarmm/fmlgEdcFarmmList"; // 농촌교육농장 리스트 불러오기 및 검색

    /**
     *
     */
    const API_NONGSARO_EDUCATION_FARMS_DETAIL = "fmlgEdcFarmm/fmlgEdcFarmmDtl"; // 농촌교육농장 상세
    /**
     *
     */
    const API_NONGSARO_WEEK_FARM_INFO = "weekFarmInfo/weekFarmInfoList"; // 주간농사정보

    /**
     *
     */
    const API_NAVER_BLOG_RSS = "rda2448.xml"; // 네이버 블로그 RSS 2.0


    /**
     * @param string $ctprvn
     * @param string $fch_knd
     * @return string
     */
    public function getMachineUrl(string $ctprvn, string $fch_knd = null) : string
    {
        $queryParams = [
            'YEAR' => 2014,
            'CTPRVN' => $ctprvn,
        ];
        if (!empty($fch_knd)) {
            $queryParams['FCH_KND'] = $fch_knd;
        }

        return (string)Uri\Uri::createFromString($this->api_call_url)
            ->withPath("/openapi/".$this->api_key.'/json/'.static::API_GRID_MACHINES.'/1/'.self::API_PAGE_LIMIT)
            ->withQuery(Uri\build_query($queryParams));
    }

    /**
     * @param string $clNm
     * @return string
     */
    public function getDictionaryUrl(string $clNm) : string
    {
        $queryParams = [
            'CL_NM' => $clNm,
        ];

        return (string)Uri\Uri::createFromString($this->api_call_url)
            ->withPath("/openapi/".$this->api_key.'/json/'.static::API_GRID_DICTIONARY.'/1/'.self::API_PAGE_LIMIT)
            ->withQuery(Uri\build_query($queryParams));
    }


    /**
     * @param int $year
     * @param string $ctprvn
     * @param string $limit
     * @return string
     */
    public function getSpecialCropsUrl(int $year, string $ctprvn, string $limit = self::API_PAGE_LIMIT) : string
    {
        $queryParams = [
            'YEAR' => $year,
            'CTPRVN' => $ctprvn,
        ];

        return (string)Uri\Uri::createFromString($this->api_call_url)
            ->withPath("/openapi/".$this->api_key.'/json/'.static::API_GRID_SPECIAL_CROPS.'/1/'.$limit)
            ->withQuery(Uri\build_query($queryParams));
    }


    /**
     * @param string $sidonm
     * @param string $gubuncd
     * @param string $
     * @param $dealtypecd
     * @param string $limit
     * @return string
     */
    public function getEmptyHousesUrl(string $sidonm, string $gubuncd, string $dealtypecd, string $limit = self::API_PAGE_LIMIT) : string
    {
        $queryParams = [
            'SIDO_NM' => $sidonm,
            'GUBUN_CD' => $gubuncd,
            'DEAL_TYPE_CD' => $dealtypecd,
        ];

        return (string)Uri\Uri::createFromString($this->api_call_url)
            ->withPath("/openapi/".$this->api_key.'/json/'.static::API_GRID_EMPTY_HOUSES.'/1/'.$limit)
            ->withQuery(Uri\build_query($queryParams));
    }

    /**
     * @param int $page
     * @param string $sType
     * @param string $sText
     * @return string
     */
    public function getEducationFarms(int $page, string $sType, string $sText) : string
    {
        $queryParams = [
            'apiKey' => $this->api_nongsaro_key,
            'pageNo' => $page,
        ];

        if ($sType !== "" && $sText !== "") {
            $queryParams['sType'] = $sType;
            $queryParams['sText'] = $sText;
        }

        return (string)Uri\Uri::createFromString($this->api_call_url_nongsaro)
            ->withPath("/service/".self::API_NONGSARO_EDUCATION_FARMS_LISTS)
            ->withQuery(Uri\build_query($queryParams));
    }

    /**
     * @param int $page
     * @return string
     */
    public function getWeekFarmInfo(int $page) : string
    {
        $queryParams = [
            'apiKey' => $this->api_nongsaro_key,
            'pageNo' => $page,
        ];

        return (string)Uri\Uri::createFromString($this->api_call_url_nongsaro)
            ->withPath("/service/".self::API_NONGSARO_WEEK_FARM_INFO)
            ->withQuery(Uri\build_query($queryParams));
    }


    /**
     * @param int $cntntsNo
     * @return string
     */
    public function getEducationFarmsDetail(int $cntntsNo) : string
    {
        $queryParams = [
            'apiKey' => $this->api_nongsaro_key,
            'cntntsNo' => $cntntsNo,
        ];

        return (string)Uri\Uri::createFromString($this->api_call_url_nongsaro)
            ->withPath("/service/".self::API_NONGSARO_EDUCATION_FARMS_DETAIL)
            ->withQuery(Uri\build_query($queryParams));
    }

    /**
     * @return string
     */
    public function getNaverBlogRss() : string
    {
        return (string)Uri\Uri::createFromString($this->api_call_url_naver_blog_rss)
            ->withPath("/".self::API_NAVER_BLOG_RSS);
    }
}
