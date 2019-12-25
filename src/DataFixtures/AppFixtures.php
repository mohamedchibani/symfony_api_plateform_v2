<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;

use App\Entity\Comment;
use App\Entity\BlogPost;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;
    private $faker;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    
    public function load(ObjectManager $manager)
    {
        $this->userLoad($manager);
        $this->postLoad($manager);
        $this->commentLoad($manager);

        $manager->flush();
    }

    public function userLoad(ObjectManager $manager){
        for($i=0; $i < 10; $i++){
            $user = new User();

            $user->setUsername($this->faker->userName);
            $user->setName($this->faker->name);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret123'));

            $this->addReference("user_admin_$i", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
    
    
    public function postLoad(ObjectManager $manager){
        for($i=0; $i < 100; $i++){
            $post = new BlogPost();
            $post->setTitle($this->faker->sentence());
            $post->setSlug($this->faker->slug());
            $post->setPublished(new \DateTime());
            $post->setContent($this->faker->realText());

            $user = $this->getReference("user_admin_".rand(0,9));
            $post->setUser($user);

            $this->addReference("post_$i", $post);

            $manager->persist($post);
        }
        $manager->flush();
    }


    public function commentLoad(ObjectManager $manager){
        for($i=0; $i < 1000; $i++){
            $comment = new Comment();
            $comment->setPublished(new \DateTime());
            $comment->setContent($this->faker->realText());

            $user = $this->getReference("user_admin_".rand(0,9));

            $post = $this->getReference("post_".rand(0,99));

            $comment->setUser($user);
            $comment->setPost($post);

            $manager->persist($comment);
        }
        $manager->flush();
    }


}

