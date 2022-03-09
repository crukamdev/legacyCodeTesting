<?php
require_once __DIR__ . '/../models/rosters.php';

use Mockery as m;

class RosterModelTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
    /**
     * @test
     */
    public function sortOrderWorksAsExpectedWithDoubles()
    {
        /** 
         * Create a query factory double
         * Create a PDO double
         * Override PDO with the double
         * Create fake results from players and pitcher query
         * Create fake results for draft picks
         * Reading  our data fixtures and
         * Create a new RosterModel object 
         * Execute the getByNickname() method 
         * Assert that Our expected roster matches what it is returned
         * 
         * */
        //Arrange
        $newSelect = m::mock(Aur\SqlQuery\Common\Select::class);
        $newSelect->shouldReceive('cols->from->where->orderBy->bindValue')->once();
        $newSelect->shouldReceive('cols->from->where->bindValue')->once();
        $db = m::mock(QueryFactory::class);
        $db->shouldReceive('newSelect')->twice()->andReturn($newSelect);
        $rawRoster = unserialize(file_get_contents(__DIR__.'/fixtures/raw_rosters_players.txt'));
        $rawPicks = unserialize(file_get_contents(__DIR__.'/fixtures/raw_rosters_picks.txt'));
        $pdoStatement = m::mock(PDOStatement::class);
        $pdoStatement->shouldReceive('fetchAll')->once()->andReturn($rawRoster);
        $pdo = m::mock(PDO::class);
        $pdo->shouldReceive('prepare')->andReturn($pdoStatement);

        //Act
        $rosterModel = new Roster($db);
        $rosterModel->pdo = $pdo;
        $roster = $rosterModel->getByNickname('MAD');

        //Assert
        $expectedRoster = unserialize(file_get_contents(__DIR__.'/fixtures/mad_roster.txt'));
        $this->assertEquals($expectedRoster, $roster);
    }
}
