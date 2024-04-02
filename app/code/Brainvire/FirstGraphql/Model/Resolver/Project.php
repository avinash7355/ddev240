<?php

declare(strict_types=1);

namespace Brainvire\FirstGraphql\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Brainvire\Projects\Model\ProjectsFactory;

/**
 * Class BlogDetails
 */
class Project implements ResolverInterface
{

    /**
     * ProjectsFactory
     *
     * @var $projectsFactory
     */
    private $projectsFactory;


    /**
     * Constructor
     *
     * @param ProjectsFactory $projectsFactory ProjectsFactory.
     */
    public function __construct(
        ProjectsFactory $projectsFactory
    ) {
        $this->projectsFactory = $projectsFactory;

    }//end __construct()


     /**
      * Resolve Function
      *
      * @param Field       $field   Field.
      * @param Context     $context Context.
      * @param ResolveInfo $info    ResolveInfo.
      * @param array       $value   Array.
      * @param array       $args    Array.
      *
      * @throws GraphQlInputException GraphQlInputException.
      * @throws GraphQlNoSuchEntityException GraphQlNoSuchEntityException.
      *
      * @return array
      */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value=null,
        array $args=null
    ) {
       
        $postData = [];

                $id         = $args['entity_id'];
                $post = $this->projectsFactory->create()->load($id);
                $postData   = $post->getData();
                // echo "<pre>";
                // print_r($postData); exit;

        return $postData;

    }//end resolve()


}//end class