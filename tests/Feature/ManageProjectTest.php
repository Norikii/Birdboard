<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class ManageProjectTest extends TestCase
{
    //WithFaker allow to use faker when writing tests
    //RefreshDatabase - after running the tests it resets everything to its initial stage
    use WithFaker, RefreshDatabase;

    /** @test */
    public function test_user_create_project()
    {
        $this->withExceptionHandling();

        // sign an user in
        $this->actingAs(factory('App\User')->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->be(factory('App\User')->create());

        $this->withExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        // if we hit the endpoint we assert we can see
        // the title of the project as well as description
        $this->get($project->path())
             ->assertSee($project->title)
             ->assertSee($project->description);
    }

    /** @test
     *If I make a post request to this endpoint and I don't give it a title
     * then I am going to assert that the session has errors.
     * Asserting a title error in a session.
     */
    public function a_project_requires_a_title()
    {
        // sign an user in
        $this->actingAs(factory('App\User')->create());

        // at this point we can pass three attributes:
        // create() - creates the attributes and save them to the DB
        // make() - creates the attributes as an object
        // raw() - creates the attributes and store them as an array
        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test
     *If I make a post request to this endpoint and I don't give it a description
     * then I am going to assert that the session has errors.
     * Asserting a description error in a session.
     */
    public function a_project_requires_a_description()
    {
        // sign an user in
        $this->actingAs(factory('App\User')->create());


        $attributes = factory('App\Project')->raw(['description' => '']);


        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test
     *If I make a post request to this endpoint and I don't give it a description
     * then I am going to assert that the session has errors.
     * Asserting a description error in a session.
     */
    public function test_guest_cannot_create_projects()
    {
//        $this->withExceptionHandling();

        $attributes = factory('App\Project')->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    public function test_guest_cannot_view_a_single_project()
    {
        $project = factory('App\Project')->create();

        $this->get($project->path())->assertRedirect('login');
    }

    public function test_user_guests_may_not_view_projects()
    {
        $this->post('/projects')->assertRedirect('login');
    }

    public function test_user_auth_user_cannot_view_project_of_others()
    {
        $this->be(factory('App\User')->create());

        $this->withExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_user_project_belongs_to_user()
    {
        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User', $project->owner);
    }
}
