Feature: Blog Post List
  As a Sihae user
  I want to be able to see a list of my blog posts
  So that users can browse my blog

  Scenario: Visiting a Sihae blog without any posts
    Given I am on the homepage
    Then I should see "There aren't any posts!"

  @database
  Scenario: Visiting a Sihae blog with a single blog post
    Given there is a post:
      | title                | summary                       | body                |
      | Penny's Perfect Post | A cool and good post by Penny | Penny's post's text |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "A cool and good post by Penny"
    And I should see text matching "posted (\d*) second(s?) ago"
    But I should not see "Penny's post's text"
    And I should not see "Newer Posts"
    And I should not see text matching "Page (\d*) of (\d*)"
    And I should not see "Older Posts"

  @database
  Scenario: Visiting a Sihae blog with multiple blog posts
    Given there are some posts:
      | title                | summary                       | body                |
      | Penny's Perfect Post | A cool and good post by Penny | Penny's post's text |
      | Penny's Alright Post | An alright(ish) post by Penny | Another post's text |
      | Penny's Premium Post | Another post that Penny wrote | Some more text      |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "A cool and good post by Penny"
    And I should see "Penny's Alright Post"
    And I should see "An alright(ish) post by Penny"
    And I should see "Penny's Premium Post"
    And I should see "Another post that Penny wrote"
    But I should not see "Penny's post's text"
    And I should not see "Another post's text"
    And I should not see "Some more text"

  @database
  Scenario: Visiting a Sihae blog with multiple pages of blog posts
    Given there are some posts:
      | title                | summary                       | body                |
      | Penny's Perfect Post | A cool and good post by Penny | Penny's post's text |
      | Penny's Alright Post | An alright(ish) post by Penny | Another post's text |
      | Penny's Premium Post | Another post that Penny wrote | Some more text      |
    And the number of posts per page is "2"
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "A cool and good post by Penny"
    And I should see "Penny's Alright Post"
    And I should see "An alright(ish) post by Penny"
    And I should see "Newer Posts"
    And I should see "Page 1 of 2"
    And I should see "Older Posts"
    But I should not see "Penny's Premium Post"
    And I should not see "Another post that Penny wrote"