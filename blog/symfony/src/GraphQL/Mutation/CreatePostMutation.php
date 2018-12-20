<?php

namespace App\GraphQL\Mutation;

use App\Entity\Post;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Validator\Validation;

use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

//use Symfony\Component\Validator\Validator\ValidatorInterface;
//, AliasedInterface
class CreatePostMutation implements MutationInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createPost($value, Argument $args, \ArrayObject $context, ResolveInfo $info)
    {
//        $container;
        [
            'title' => $title,
            'text' => $text
            // TODO попробовать так: $args['input']
        ] = $args->offsetGet('input');

        // Do something with `$shipName` and `$factionId` ...
        $post = new Post();
        $post->setTitle($title);
        $post->setText($text);
        // $post->setTextShort();
        // ...

//        dump($post);

        $validator = $this->container->get('validator'); // Validation::createValidator(); // $container->get('valdiator'); // Validation::createValidator();
        $errors = $validator->validate($post);
//        \Symfony\Component\DependencyInjection\ContainerInterface
//        dump($post, $errors);

//        dump($errors);
        $errorsResponse = [];

        if (\count($errors)) {
            foreach ($errors as $error) {
                $errorsResponse[] = [
                    'key' => $error->getPropertyPath(),
                    'message' => $error->getMessage()
                ];
            }

//            $errorsString = (string) $errors;

//            dump($errorsString);

            // TODO ошибки в graphql можно показывать намного лучше, не успел это изучить
//            throw new \Error($errorsString);
        }

        // Then returns our payload, it should fits `IntroduceShipPayload` type
        return [
            'post' => [
                'title' => $title,
                'text' => $text,
            ],
            'errors' => $errorsResponse
        ];
    }

    /**
     * {@inheritdoc}
     */
//    public static function getAliases()
//    {
//        return [
//            // `create_ship` is the name of the mutation that you SHOULD use inside of your types definition
//            // `createShip` is the method that will be executed when you call `@=resolver('create_ship')`
//            'createShip' => 'create_ship'
//        ];
//    }
}
