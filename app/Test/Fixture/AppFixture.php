<?php
/**
 * App Fixture
 *
 */
class AppFixture extends CakeTestFixture {

/**
 * Add foreign key constraints after creating the table.
 *
 * @param DboSource $db An instance of the database object
 * @return void
 * @see CakeTestFixture::create()
 */
	public function create($db) {
		parent::create($db);

		ClassRegistry::init($this->name)->addForeignKeyConstraints($db);
	}

/**
 * Temporarily disable foreign key checks to make truncate possible.
 *
 * @param DboSource $db An instance of the database object
 * @return void
 * @see CakeTestFixture::truncate()
 */
	public function truncate($db) {
		$db->execute('SET FOREIGN_KEY_CHECKS = 0;');

		$return = parent::truncate($db);

		$db->execute('SET FOREIGN_KEY_CHECKS = 1;');

		return $return;
	}

/**
 * Drop foreign key constraints before dropping the table.
 *
 * @param DboSource $db An instance of the database object
 * @return void
 * @see CakeTestFixture::drop()
 */
	public function drop($db) {
		ClassRegistry::init($this->name)->dropForeignKeyConstraints($db);

		return parent::drop($db);
	}

}
