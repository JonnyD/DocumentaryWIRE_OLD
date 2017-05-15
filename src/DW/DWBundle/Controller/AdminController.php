<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Cache\ImageCache;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use DW\DWBundle\Entity\Video;
use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Entity\ActivityType;
use DW\DWBundle\Entity\VoteType;
use DW\DWBundle\Helper\Directories;
use DW\DWBundle\Helper\Helpers;
use DW\DWBundle\Manager\Managers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function showDashboardAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getAllDocumentaries();

        return $this->render('DocumentaryWIREBundle:Admin:dashboard.html.twig', array(
            'documentaries' => $documentaries
        ));
    }

    public function showDocumentaryAction($slug)
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentary = $documentaryManager->getDocumentaryBySlug($slug);

        return $this->render('DocumentaryWIREBundle:Admin:showDocumentary.html.twig', array(
            'documentary' => $documentary
        ));
    }

    public function createDocumentaryAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('title', 'text')
            ->add('slug', 'text', array('required' => false))
            ->add('description', 'textarea')
            ->add('excerpt', 'textarea')
            ->add('length', 'text')
            ->add('year', 'text')
            ->add('thumbnail', 'file', array(
                'data_class' => null
            ))
            ->add('category', 'entity', array(
                'class' => 'DocumentaryWIREBundle:Category',
                'property' => 'name'
            ))
            ->add('status', 'choice', array(
                'choices'   => array('publish' => 'Publish', 'draft' => 'Draft')
            ))
            ->add('url', 'text', array('mapped' => false))
            ->add('shortUrl', 'text')
            ->add('save', 'submit')
            ->getForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $data = $form->getData();

            if ($form->isValid()) {
                $title = $data["title"];
                $description = $data["description"];
                $slug = $data["slug"];
                $excerpt = $data["excerpt"];
                $status = $data["status"];
                $shortUrl = $data["shortUrl"];
                $category = $data["category"];
                $length = $data["length"];
                $year = $data["year"];
                $thumbnail = $data["thumbnail"];
                $featuredImage = $data["featuredImage"];

                $documentary = new Documentary();
                $documentary->setCreated(new \DateTime());
                $documentary->setUpdated(new \DateTime());
                $documentary->setTitle($title);
                $documentary->setDescription($description);
                $documentary->setExcerpt($excerpt);
                $documentary->setSeoTitle($documentary->getTitle());
                $documentary->setSeoDescription($documentary->getExcerpt());
                $documentary->setSlug($slug);
                $documentary->setCategory($category);
                $documentary->setStatus($status);
                $documentary->setShortUrl($shortUrl);
                $documentary->setLength($length);
                $documentary->setYear($year);

                $imageHelper = $this->get(Helpers::IMAGE);
                $thumbnail = $imageHelper->uploadFile($thumbnail, ImageCache::COVER_160x200,
                        Directories::DOCUMENTARY_THUMBNAIL, $documentary->getSlug());
                $documentary->setThumbnail($thumbnail);

                $url = $form->get('url')->getData();
                $purl = new \Purl\Url($url);

                $source = strstr($purl->registerableDomain, ".", true);
                $documentary->setVideoSource($source);
                $documentary->setVideoId($purl->query->getData()['v']);

                $userManager = $this->get(Managers::USER);
                $user = $userManager->getUserByUsername("docubot");
                $documentary->setAddedBy($userManager->merge($user));

                $documentaryManager = $this->get(Managers::DOCUMENTARY);
                $documentaryManager->addDocumentary($documentary);
            }
        }

        return $this->render('DocumentaryWIREBundle:Admin:createDocumentary.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function migrateFromWordpressAction()
    {
        set_time_limit(999999);
        /*$settings = array(
            'oauth_access_token' => "MY_OAUTH_ACCESS_TOKEN",
            'oauth_access_token_secret' => "MY_OAUTH_ACCESS_TOKEN_SECRET",
            'consumer_key' => "MY_CONSUMER_KEY",
            'consumer_secret' => "MY_CONSUMER_SECRET"
        );

        $url = "https://api.twitter.com/1.1/statuses/update_with_media.json";
        $postfields = array('status' => 'Watch Documentary: And Man Created Dog',
            'media'  => @file_get_contents('/location'));
        $requestMethod = 'POST';

        $twitter = new \TwitterAPIExchange($settings);
        $response =  $twitter->buildOauth($url, $requestMethod)
            ->setPostfields($postfields)
            ->performRequest();

        var_dump(json_decode($response));*/

        //$this->migrateCommentVotes();
        //$this->migrateRoles();
        //$this->migrateActivity();
        //$this->migrateUsers();
        //$this->migrateCategories();
        //$this->migrateDocumentaries();
        //$this->migrateComments();
        //$this->migrateTags();
        $this->migrateActivityGrouping();
        //$this->migrateFacebookAvatars();
        //$this->migrateLikeTime();

        return $this->render('DocumentaryWIREBundle:Admin:migrateFromWordpress.html.twig', array(

        ));
    }

    private function migrateComments()
    {
        $commentManager = $this->get('documentary_wire.comment_manager');
        $comments = $commentManager->getAllComments();

        foreach ($comments as $comment) {
            $author = $comment->getAuthor();
            if ($author == "" || $author == null) {
                $comment->setAuthor(null);
                $commentManager->persistOnly($comment);
            }
        }

        $commentManager->flush();
    }

    private function migrateRoles()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getAllUsers();

        $roleManager = $this->get('documentary_wire.role_manager');
        $userRole = $roleManager->getRoleByName("user");

        foreach ($users as $user) {
            $user->addRole($userRole);
            $userManager->persistOnly($user);
        }

        $userManager->flush();
    }

    private function migrateFacebookAvatars()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getUsersWithFacebook();

        foreach ($users as $user) {
            $avatar = $user->getAvatar();
            $facebookId = $user->getFacebookId();

            $img = $facebookId . '.jpg';
            $path = 'uploads/images/avatar/';
            $url = 'http://localhost/DocumentaryWIRE2/web/'.$path.$img;
            if ($avatar == null && $avatar != $img) {
                print $url . "<br/>";
                $headers = @get_headers($url);
                $responseCode = substr($headers[0], 9, 3);
                if($responseCode != "404"){
                    $user->setAvatar($img);
                    $userManager->persistOnly($user);
                    print "success <br/>";
                }else{
                    print "error <br/>";
                }
            }
        }

        $userManager->flush();
    }

    private function migrateLikeTime()
    {

    }

    private function migrateTags()
    {
        $tagManager = $this->get('documentary_wire.tag_manager');
        $tags = $tagManager->getAllTags();

        $documentaryManager = $this->get('documentary_wire.documentary_manager');

        foreach ($tags as $tag) {
            $documentaries = $documentaryManager->getDocumentariesByTag($tag);
            $count = count($documentaries);
            $tag->setCount($count);
            $tagManager->persistOnly($tag);
        }

        $tagManager->flush();
    }

    private function migrateDocumentaries()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getAllDocumentaries();

        $likeManager = $this->get('documentary_wire.like_manager');
        $commentManager = $this->get('documentary_wire.comment_manager');

        foreach ($documentaries as $documentary) {
            $likes = $likeManager->getLikesByDocumentary($documentary);
            $likeCount = count($likes);
            $documentary->setLikeCount($likeCount);
            $comments = $commentManager->getCommentsByDocumentary($documentary);
            $commentCount = count($comments);
            $documentary->setCommentCount($commentCount);
            $documentaryManager->persistOnly($documentary);
        }

        foreach ($documentaries as $documentary) {
            $oldThumbnail = $documentary->getOldThumbnail();
            $newThumbnail = $documentary->getThumbnail();

            if ($newThumbnail == null && $oldThumbnail != null) {
                var_dump($documentary);
            echo $documentary->getTitle() . "<br />";
            echo "new" . $newThumbnail . "<br />";
            echo "old" . $oldThumbnail . "<br />";
                print_r("hello");
                $documentary->setThumbnail($documentary->getSlug() . ".jpg");
                $documentaryManager->persistOnly($documentary);
            }
        }

        $documentaryManager->flush();
    }

    private function migrateCategories()
    {
        $categoryManager = $this->get('documentary_wire.category_manager');
        $categories = $categoryManager->getAllCategories();

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        foreach ($categories as $category) {
            $documentaries = $documentaryManager->getDocumentaryKeysByCategory($category, DocumentaryStatus::PUBLISH,
                Order::DESC, DocumentaryFilter::TITLE, -1);
            $count = count($documentaries);
            $category->setCount($count);
            $categoryManager->persistOnly($category);
        }
        $categoryManager->flush();
    }

    private function migrateUsers()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getUsersWithFacebook();

        foreach ($users as $user) {
            $avatar = $user->getAvatar();
            $facebookId = $user->getFacebookId();

            $url = $user->getFacebookAvatarFull();
            $img = $facebookId . '.jpg';
            $path = 'uploads/images/avatar/';
            if ($avatar == null) {
                print $url . "<br/>";
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $headers = get_headers($url);
                    $responseCode = substr($headers[0], 9, 3);
                    if($responseCode != "404"){
                        $contents = @file_get_contents($url);
                        if ($contents != false) {
                            file_put_contents($path . $img, $contents);
                            $user->setAvatar($img);
                            $userManager->persistOnly($user);
                            print "success <br/>";
                        }
                    }else{
                        print "error <br/>";
                    }
                }
            }
        }

        $userManager->flush();
    }

    private function migrateActivity()
    {
        $activityManager = $this->get('documentary_wire.activity_manager');
        $activity = $activityManager->getRawActivity();

        foreach ($activity as $activityItem) {
            $id = $activityItem['id'];
            $data = $activityItem['data'];
            $dataItems = explode("|+|", $data);

            $dataArray = array();
            foreach ($dataItems as $dataItem) {
                $pairs = explode("|~|", $dataItem);
                $key = $pairs[0];
                $value = $pairs[1];
                $dataArray[$key] = $value;
            }

            $activityObject = $activityManager->getActivityItem($id);
            $activityObject->setData(serialize($dataArray));
            $activityManager->persistOnly($activityObject);
        }

        $activityManager->flush();
    }

    private function migrateActivityGrouping()
    {
        $activityManager = $this->get('documentary_wire.activity_manager');
        $activity = $activityManager->getActivityOrderedByDateASC();

        $group = 1;
        $previousActivityItem = null;
        foreach ($activity as $activityItem) {
            $type = $activityItem->getType();
            $user = $activityItem->getUser();

            $to = $activityItem->getCreated();
            $from = clone $to;
            var_dump($to);
            echo "<br />";
            var_dump($from);
            echo "<br />";
            echo "<br />";
            echo "<br />";
            if ($type == "like") {
                if ($previousActivityItem->getUser() == $user &&
                    $previousActivityItem->getType() == 'like') {
                        $oldGroup = $previousActivityItem->getGroupNumber();
                        $activityItem->setGroupNumber($oldGroup);
                        $activityManager->persistOnly($activityItem);
                } else {
                    $from->sub(new \DateInterval('P3D'));
                    $relatedActivity = $activityManager->getActivityByUserAndTypeBetweenDates($user, $type, $from, $to);
                    foreach ($relatedActivity as $relatedActivityItem) {
                        $relatedActivityItem->setGroupNumber($group);
                        $activityManager->persistOnly($relatedActivityItem);
                    }
                }
            } else if ($type == "joined") {
                if ($previousActivityItem->getType() == 'joined') {
                    $oldGroup = $previousActivityItem->getGroupNumber();
                    $activityItem->setGroupNumber($oldGroup);
                    $activityManager->persistOnly($activityItem);
                } else {
                    $activityItem->setGroupNumber($group);
                    $activityManager->persistOnly($activityItem);
                }
            } else if ($type == "comment") {
                $activityItem->setGroupNumber($group);
                $activityManager->persistOnly($activityItem);
            }

            $group++;
            $previousActivityItem = $activityItem;
        }
        $activityManager->flush();
    }

    public function migrateCommentVotes()
    {
        $commentManager = $this->get('documentary_wire.comment_manager');
        $comments = $commentManager->getAllComments();

        $voteManager = $this->get('documentary_wire.vote_manager');

        foreach ($comments as $comment) {
            $user = $comment->getUser();
            if ($user == null) {
                $voteManager->createVote(null, $comment, VoteType::UPVOTE);
            } else {
                $voteManager->createVote($user, $comment, VoteType::UPVOTE);
            }
        }
        $voteManager->flush();
    }
}