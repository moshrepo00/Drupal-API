<?php

namespace Drupal\api_chat\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\api_chat\Entity\ChatEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChatEntityController.
 *
 *  Returns responses for Chat entity routes.
 */
class ChatEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Chat entity revision.
   *
   * @param int $chat_entity_revision
   *   The Chat entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($chat_entity_revision) {
    $chat_entity = $this->entityTypeManager()->getStorage('chat_entity')
      ->loadRevision($chat_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('chat_entity');

    return $view_builder->view($chat_entity);
  }

  /**
   * Page title callback for a Chat entity revision.
   *
   * @param int $chat_entity_revision
   *   The Chat entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($chat_entity_revision) {
    $chat_entity = $this->entityTypeManager()->getStorage('chat_entity')
      ->loadRevision($chat_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $chat_entity->label(),
      '%date' => $this->dateFormatter->format($chat_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Chat entity.
   *
   * @param \Drupal\api_chat\Entity\ChatEntityInterface $chat_entity
   *   A Chat entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ChatEntityInterface $chat_entity) {
    $account = $this->currentUser();
    $chat_entity_storage = $this->entityTypeManager()->getStorage('chat_entity');

    $build['#title'] = $this->t('Revisions for %title', ['%title' => $chat_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all chat entity revisions") || $account->hasPermission('administer chat entity entities')));
    $delete_permission = (($account->hasPermission("delete all chat entity revisions") || $account->hasPermission('administer chat entity entities')));

    $rows = [];

    $vids = $chat_entity_storage->revisionIds($chat_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\api_chat\ChatEntityInterface $revision */
      $revision = $chat_entity_storage->loadRevision($vid);
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $chat_entity->getRevisionId()) {
          $link = \Drupal\Core\Link::fromTextAndUrl($date, new Url('entity.chat_entity.revision', [
            'chat_entity' => $chat_entity->id(),
            'chat_entity_revision' => $vid,
          ]));
        }
        else {
          $link = \Drupal\Core\EntityInterface::toLink($chat_entity)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('entity.chat_entity.revision_revert', [
                'chat_entity' => $chat_entity->id(),
                'chat_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.chat_entity.revision_delete', [
                'chat_entity' => $chat_entity->id(),
                'chat_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
    }

    $build['chat_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
