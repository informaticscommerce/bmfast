<?php
/**
 * Created By : Rohan Hapani
 */
namespace Mage4\ExtendMagentoCms\Block;

class CmsPageData extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepositoryInterface;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Api\PageRepositoryInterface         $pageRepositoryInterface
     * @param \Magento\Framework\Api\SearchCriteriaBuilder     $searchCriteriaBuilder
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Return CMS Page Details by URL Key
     *
     * @param  string $urlKey
     * @return string
     */
    public function getCmsPageDetails($urlKey)
    {
        if(!empty($urlKey))
        {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('identifier', $urlKey,'eq')->create();
            $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
            return $pages;
        }
        else
        {
            return 'Page URL Key is invalid';
        }
    }
}
