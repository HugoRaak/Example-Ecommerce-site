<?php declare(strict_types=1);
namespace Framework\Database\Table;

use Envms\FluentPDO\Queries\Select;
use Framework\Database\Entity\Article;
use Framework\Helper;
use Pagerfanta\Pagerfanta;

/**
 * @extends Table<static, Article>
 */
final class ArticleTable extends Table
{
    protected ?string $table = 'article';

    protected ?string $entity = Article::class;

    /**
     * return query for PaginatedQuery
     * @return Select
     */
    protected function paginationQuery(): Select
    {
        return parent::paginationQuery()->orderBy('created_at DESC');
    }

    /**
     * Find articles of a user
     * @param int $id
     *
     * @return mixed
     */
    public function findArticleFromUser(int $id, ?string $orderBy = null)
    {
        $query = $this->makeQuery()->where('user.id', $id)->select('article.*');
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
        $statement = $query->execute();
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $statement->fetchAll();
    }

    /**
     * Get paginated articles from a category ID
     *
     * @param int $perPage
     * @param int $currentPage
     * @param int $id
     *
     * @return Pagerfanta<\Pagerfanta\PagerfantaInterface>
     */
    public function findPaginatedFromCategorie(int $perPage, int $currentPage, int $id): Pagerfanta
    {
        return $this->findPaginated(
            $perPage,
            $currentPage,
            $this->makeQuery()->where('categorie.id', $id)->select('article.*'),
            $this->makeQuery()->where('categorie.id', $id)->select(null)->select('COUNT(article.id)')
        );
    }

    /**
     * search correspondance in table article and the params $search
     * @param string $search
     *
     * @return Article[]
     */
    public function getArticlesFromSearch(string $search): array
    {
        $articles = $this->findAll();
        $search = Helper::clearStr($search);
        $articles_found = [];

        foreach ($articles as $article) {
            $name = Helper::clearStr($article->__get('name'));
            $descri = Helper::clearStr($article->__get('description'));
            $accuracy = 0;

            if ($name === $search) {
                $accuracy = 9;
            } else {
                $wordInName = explode(' ', $name);
                foreach ($wordInName as $word) {
                    if ($word === $search) {
                        $accuracy = 8;
                        break;
                    }
                }
                if ($accuracy === 0) {
                    $wordInDescri = explode(' ', $descri);
                    foreach ($wordInDescri as $word) {
                        if ($word === $search) {
                            $accuracy = 7;
                            break;
                        }
                    }
                    if ($accuracy === 0) {
                        $wordInSearch = explode(' ', $search);
                        foreach ($wordInSearch as $word) {
                            if ($word === $name) {
                                $accuracy = 6;
                                break;
                            }
                        }
                        if ($accuracy === 0) {
                            foreach ($wordInSearch as $word) {
                                foreach ($wordInName as $wordName) {
                                    if ($word === $wordName) {
                                        $accuracy = 5;
                                        break;
                                    }
                                }
                            }
                            if ($accuracy === 0) {
                                foreach ($wordInSearch as $word) {
                                    if ($word === $descri) {
                                        $accuracy = 4;
                                        break;
                                    }
                                }
                                if ($accuracy === 0) {
                                    foreach ($wordInSearch as $word) {
                                        foreach ($wordInDescri as $wordDescri) {
                                            if ($word === $wordDescri) {
                                                $accuracy = 3;
                                                break;
                                            }
                                        }
                                    }
                                    if ($accuracy === 0) {
                                        if (strpos($name, $search) !== false) {
                                                $accuracy = 2;
                                        } else {
                                            if (strpos($descri, $search) !== false) {
                                                $accuracy = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($accuracy > 0) {
                $articles_found[] = [
                    'article' => $article,
                    'accuracy' => $accuracy
                ];
            }
        }

        $maxAccuracy = 0;
        $articles_highest_accuracy = [];
        foreach ($articles_found as $article) {
            if ($article['accuracy'] > $maxAccuracy) {
                $maxAccuracy = $article['accuracy'];
            }
        }
        foreach ($articles_found as $article) {
            if ($article['accuracy'] === $maxAccuracy) {
                $articles_highest_accuracy[] = $article['article'];
            }
        }
        /** @var Article[] $articles_highest_accuracy */
        return $articles_highest_accuracy;
    }
}
