<?php 
   class Post {
      private $conn;
      private $table = 'posts';

      // Post Properties
      public $id;
      public $title;
      public $body;
      public $author;
      public $created_at;

      // Constructor with DB
      public function __construct($db) {
         $this->conn = $db;
      }

      // Get all Posts
      public function get_all() {
         // Create query
         $query = 'SELECT p.id, p.title, p.body, p.author, p.created_at FROM ' . $this->table . ' p ORDER BY p.created_at DESC';
         
         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Execute query
         $stmt->execute();

         return $stmt;
      }

      // Get Single Post
      public function get_single() {
         // Create query
         $query = 'SELECT p.id, p.title, p.body, p.author, p.created_at FROM ' . $this->table . ' p WHERE p.id = ? LIMIT 0,1';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Bind ID
         $stmt->bindParam(1, $this->id);

         // Execute query
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         // Set properties
         $this->title = $row['title'];
         $this->body = $row['body'];
         $this->author = $row['author'];
      }

      // Create Post
      public function create() {
         // Create query
         $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Clean input data
         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->body = htmlspecialchars(strip_tags($this->body));
         $this->author = htmlspecialchars(strip_tags($this->author));

         // Bind data
         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':body', $this->body);
         $stmt->bindParam(':author', $this->author);

         // Execute query
         if($stmt->execute()) {
            return true;
         } else {
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
         }
      }

      // Update Post
      public function update() {
         $query = 'UPDATE ' . $this->table . ' SET title = :title, body = :body, author = :author WHERE id = :id';
         $stmt = $this->conn->prepare($query);

         // Clean input data
         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->body = htmlspecialchars(strip_tags($this->body));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->id = htmlspecialchars(strip_tags($this->id));

         // Bind data
         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':body', $this->body);
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':id', $this->id);

         if($stmt->execute()) {
            return true;
         } else {
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
         }
      }

      // Delete Post
      public function delete() {
         $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
         $stmt = $this->conn->prepare($query);

         // Clean input data
         $this->id = htmlspecialchars(strip_tags($this->id));

         $stmt->bindParam(':id', $this->id);

         if($stmt->execute()) {
            return true;
         } else {             
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);        
            return false;
         }
      }  
   }