<?php

namespace App\GraphQL\Mutation;

use App\Entity\Post;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Validator\Validation;

use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;

//, AliasedInterface
class TestMutation implements MutationInterface
{
    // TODO параметры передаются по принципу Autowiring (Defining Services Dependencies Automatically )
    // возможно эти аргументы строго прописаны где-то в конфиге
    // убрать не нужные аргументы, в частности $value и с Query тоже
    public static function createPost($value, Argument $args, \ArrayObject $context, ResolveInfo $info)
    {
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

        $validator = Validation::createValidator();
        $errors = $validator->validate($post);

        dump($errors);

        if (\count($errors)) {
//            foreach ($errors as $error) {
//                echo $error->getMessage().'<br>';
//            }

            $errorsString = (string) $errors;

            // TODO ошибки в graphql можно показывать намного лучше, не успел это изучить
            throw new \Error($errorsString);
        }

        // Then returns our payload, it should fits `IntroduceShipPayload` type
        return [
            'title' => $title,
            'text' => $text
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
