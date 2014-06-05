<?php

namespace Piwi\System\UserBundle\Security\Core\User;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Bundle\GaufretteBundle\FilesystemMap;

class FOSUBUserProvider extends BaseClass
{
    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap
     */
    protected $filesystem;

    /**
     * Cache manager
     */
    protected $cacheManager;

    public function __construct($cacheManager, FilesystemMap $filesystem, UserManagerInterface $userManager, array $properties)
    {
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        parent::__construct($userManager, $properties);
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setterId = $setter.'Id';
        $setterToken = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setterId(null);
            $previousUser->$setterToken(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setterId($username);
        $user->$setterToken($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        // This is only possible if you use one resource because the array is different for every resource
        $fullResponse = $response->getResponse();
        $username = $response->getUsername();
        // Find user by facebookId (When you use multiple authentication methods you can use email instead
        // to allow multiple resources for one user)
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setterId = $setter.'Id';
            $setterToken = $setter.'AccessToken';
            
            // create new user here
            /** @var $user \Piwi\System\UserBundle\Entity\User */
            $user = $this->userManager->createUser();
            $user->$setterId($username);
            $user->$setterToken($response->getAccessToken());

            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setEmail($fullResponse['email']);
            $user->setPlainPassword($username . rand(999,999999));
            $user->setFirstName($fullResponse['first_name']);
            $user->setLastName($fullResponse['last_name']);
            $user->setFacebookLink($fullResponse['link']);
            if (!empty($fullResponse['birthday'])) {
                $user->setBirthday(new \DateTime($fullResponse['birthday']));
            }
            $user->setLocale($fullResponse['locale']);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
        }

        // Save profile picture
        $profilePictureFs = $this->filesystem->get('profile_pictures');
        $profilePictureFs->write($username . '.jpg', file_get_contents(
            $fullResponse['picture']['data']['url']
        ), true);

        // Remove cache!
        $this->cacheManager->remove('uploads/profile_pictures/' . $username . '.jpg', 'profile_picture');
        $this->cacheManager->remove('uploads/profile_pictures/' . $username . '.jpg', 'profile_picture_xs');
        $this->cacheManager->remove('uploads/profile_pictures/' . $username . '.jpg', 'profile_picture_s');

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        //Give Pi Wi always all roles
        if ($user->getUsername() == 100000909925278) {
            $user->setRoles(array(
                'ROLE_USER',
                'ROLE_MEMBER',
                'ROLE_ADMIN',
                'ROLE_SUPER_ADMIN'
            ));
            $this->userManager->updateUser($user);
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());
        //update profile picture
        $profilePictureFs = $this->filesystem->get('profile_pictures');
        $profilePictureFs->write($username . '.jpg', file_get_contents(
            $fullResponse['picture']['data']['url']
        ), true);

        return $user;
    }

}
