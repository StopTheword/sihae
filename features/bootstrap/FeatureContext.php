<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

use Sihae\Post;
use Sihae\User;
use Sihae\BlogConfig;
use Illuminate\Support\Facades\Config;
use Sihae\Http\Controllers\Auth\AuthController;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements
    Context,
    SnippetAcceptingContext
{
    /**
     * @BeforeSuite
     */
    public static function prepare()
    {
        self::cleanDB();
    }

    /**
     * @BeforeScenario
     */
    public static function flushCache()
    {
        Cache::flush();
    }

    /**
     * @BeforeScenario @database
     */
    public static function cleanDB()
    {
        BlogConfig::truncate();
        Post::truncate();
        User::truncate();
    }

    /**
     * @BeforeScenario @login
     */
    public function login()
    {
        $this->createTestUser();

        $this->visit('/login');
        $this->fillField('email', 'test@test.com');
        $this->fillField('password', 'testing');
        $this->pressButton('Login');
    }

    /**
     * Adds a test user to the database
     */
    protected function createTestUser()
    {
        $auth = new AuthController;
        $auth->create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'testing'
        ]);
    }

    /**
     * @Given my blog is called :blogTitle
     */
    public function myBlogIsCalled(string $blogTitle)
    {
        BlogConfig::set('title', $blogTitle);
    }

    /**
     * @Given there is a post:
     */
    public function thereIsAPost(TableNode $posts)
    {
        $this->thereAreSomePosts($posts);
    }

    /**
     * @Given there are some posts:
     */
    public function thereAreSomePosts(TableNode $posts)
    {
        $posts = $posts->getHash();
        foreach ($posts as $content) {
            $post = new Post;
            $post->title = $content['title'];
            $post->summary = $content['summary'];
            $post->body = $content['body'];
            $post->save();
        }
    }

    /**
     * @Given the number of posts per page is :postsPerPage
     */
    public function theNumberOfPostsPerPageIs(string $postsPerPage)
    {
        BlogConfig::set('postsPerPage', $postsPerPage);
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->login();
    }

    /**
     * @Given I rename my blog to :name
     */
    public function iRenameMyBlogTo(string $name)
    {
        $this->fillField('title', $name);
        $this->pressButton('Save');
    }

    /**
     * @Given I summarise my blog as :summary
     */
    public function iSummariseMyBlogAs(string $summary)
    {
        $this->fillField('summary', $summary);
        $this->pressButton('Save');
    }

    /**
     * @Given I turn :state the login link
     */
    public function iTurnTheLoginLink(string $state)
    {
        if (strtolower($state) === "on") {
            $this->checkOption('showLoginLink');
        } else {
            $this->uncheckOption('showLoginLink');
        }

        $this->pressButton('Save');
    }
}
