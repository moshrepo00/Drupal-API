<?php

namespace Drupal\api_user\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\rest\ResourceResponse;
use Drupal\user\Entity\User;



/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "user_sign_up",
 *   label = @Translation("User reset password resource."),
 *   uri_paths = {
 *     "create" = "/auth/user_sign_up",
 *   }
 * )
 */
class UserSignUp extends ResourceBase
{

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('api_user');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * Responds to POST requests.
   *
   * @param string $payload
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data)
  {


    $username = $data['username'];
    $password = $data['password'];
    $uid = \Drupal::service('user.auth')->authenticate($username, $password);


    $user_wrap = User::load($uid);
    user_login_finalize($user_wrap);


    $currentUser = \Drupal::currentUser();

    return (new ModifiedResourceResponse($data));

     
  }

}
