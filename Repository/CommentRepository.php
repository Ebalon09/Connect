<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 26.11.18
 * Time: 17:43
 */

class CommentRepository extends BaseRepository
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * LikeRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->tweetRepository = new TweetRepository();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $result = $this->database->query("SELECT * FROM Comments");

        $tweets = [];
        foreach($result as $data)
        {
            $tweet = $this->arrayToObject($data);

            $tweets[] = $tweet;
        }
        return $tweets;
    }

    /**
     * @param $data
     * @return Comment
     */
    protected function arrayToObject($data)
    {
        $comment = new Comment();
        $comment->setId($data['id']);
        $comment->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $comment->setTweet($this->tweetRepository->findOneBy(['id' => $data['tweetid']]));
        $comment->setComment($data['comment']);

        return $comment;
    }

    /**
     * @param Comment $model
     * @return array
     */
    protected function objectToArray($model)
    {
        $data = [
            'id' => $model->getId(),
            'userid' => $model->getUser()->getId(),
            'tweetid' => $model->getTweet()->getId(),
            'comment' => $model->getComment(),
        ];
        return $data;
    }

    /**
     * @param Comment $comment
     * @return mixed
     */
    public function remove(Comment $comment)
    {
        $data = $this->objectToArray($comment);
        $data2['id'] = $data['id'];

        $query = "DELETE FROM Comments ";
        $query .= "WHERE id = :id";
        return $this->database->insert($query, $data2);
    }

    /**
     * @return mixed
     */
    protected function getTableName()
    {
        return 'Comments';
    }

    /**
     * @param $model
     * @return bool
     */
    protected function isSupported($model)
    {
        return $model instanceof Comment;
    }













}