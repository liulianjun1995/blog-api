<?php

namespace App\Validates;

use App\Enums\ArticleConstants;
use Illuminate\Validation\Rule;

class ArticleValidate extends Validate
{
    protected $message = [
        'title.required'        => '请填写文章标题',
        'title.min'             => '文章标题至少4个字符',
        'title.max'             => '文章标题至多50个字符',
        'abstract.required'     => '请填写文章摘要',
        'abstract.min'          => '文章摘要至少4个字符',
        'abstract.max'          => '文章摘要至多150个字符',
        'category_id.required'  => '请选择文章分类',
        'category_id.exists'    => '文章分类不存在',
        'cover'                 => '请上传文章封面',
        'content.required'      => '请填写文章内容',
        'top.in'                => '置顶参数不合法',
        'recommend.in'          => '推荐参数不合法',
        'status.in'             => '状态参数不合法',
    ];

    /**
     * 新增验证器
     * @param array $request
     * @return bool|string
     */
    public function store($request = [])
    {
        $rules = [
            'title'         => ['required', 'min:4', 'max:50'],
            'abstract'      => ['required', 'min:4', 'max:150'],
            'category_id'   => ['required', 'exists:category,id'],
            'cover'         => ['required'],
            'content'       => ['required'],
            'is_top'        => Rule::in([0, 1]),
            'is_recommend'  => Rule::in([0, 1]),
            'status'        => Rule::in(array_keys(ArticleConstants::ARTICLE_STATUS))
        ];

        return $this->validate($request, $rules, $this->message);
    }

    /**
     * 更新验证器
     * @param int $id
     * @param array $request
     * @return bool|string
     */
    public function update($id = 0, $request = [])
    {
        if ($id <= 0) {
            return false;
        }

        $rules = [
            'title'         => ['sometimes', 'required', 'min:4', 'max:50'],
            'abstract'      => ['sometimes', 'required', 'min:4', 'max:150'],
            'category_id'   => ['sometimes', 'required', 'exists:category,id'],
            'cover'         => ['sometimes', 'required'],
            'content'       => ['sometimes', 'required'],
            'top'           => ['sometimes', Rule::in([0, 1])],
            'recommend'     => ['sometimes', Rule::in([0, 1])],
            'status'        => ['sometimes', Rule::in(array_keys(ArticleConstants::ARTICLE_STATUS))],
        ];

        return $this->validate($request, $rules, $this->message);
    }
}
