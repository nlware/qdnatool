<?php
App::uses('AppController', 'Controller');
/**
 * Images Controller
 *
 * @property Image $Image
 */
class ImagesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->request->action == 'capture' || $this->request->action == 'upload') {
			$this->Components->disable('Security');
		}
	}

	public function capture() {
		if ($_POST["save"]) {
			$type = $_POST["type"];
			if ($_POST["name"] && ($type == "JPG" || $type == "PNG")) {
				$extension = strtolower($type);
				$img = base64_decode($_POST["image"]);

				$fileType = 'image/jpeg';
				if ($type == 'PNG') {
					$fileType = 'image/png';
				}

				$data = array(
					'filename' => $_POST["name"],
					'extension' => $extension,
					'filesize' => strlen($img),
					'file_type' => $fileType
				);

				$this->Image->create();
				if ($this->Image->save($data)) {
					$file = Image::UPLOAD_DIRECTORY . $this->Image->id . '.' . $extension;
					$fh = fopen($file, 'w');
					fwrite($fh, $img);
					fclose($fh);
				}

				echo Router::url(array('action' => 'get', $this->Image->id));
				exit;
			}
		} else {
			header('Content-Type: image/jpeg');
			echo base64_decode($_POST["image"]);
		}
	}

	public function get($id) {
		$image = $this->Image->find(
			'first', array(
				'conditions' => array(
					'Image.id' => $id
				)
			)
		);

		if (empty($image)) {
			return $this->redirect404Error();
		}

		$this->response->file(
			Image::UPLOAD_DIRECTORY . $image['Image']['id'] . '.' . $image['Image']['extension'], array(
				'download' => true,
				'name' => $image['Image']['filename']
			)
		);
		$this->response->cache($image['Image']['created'], '+30 days');
		return $this->response;
	}

	public function upload() {
		// Required: anonymous function reference number as explained above.
		$funcNum = $_GET['CKEditorFuncNum'];
		// Optional: instance name (might be used to load a specific configuration file or anything else).
		$CKEditor = $_GET['CKEditor'];
		// Optional: might be used to provide localized messages.
		$langCode = $_GET['langCode'];

		if (isset($_FILES['upload']) && $_FILES['upload']['error'] == UPLOAD_ERR_OK) {
			$extension = $this->__getExtension($_FILES['upload']["name"]);

			$data = array(
				'filename' => $_FILES['upload']["name"],
				'extension' => $extension,
				'filesize' => $_FILES['upload']['size'],
				'file_type' => $_FILES['upload']['type']
			);

			$this->Image->create();
			if ($this->Image->save($data)) {
				$file = Image::UPLOAD_DIRECTORY . $this->Image->id . '.' . $extension;
				$result = move_uploaded_file($_FILES['upload']['tmp_name'], $file);
			}

			// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
			$url = Router::url(array('action' => 'get', $this->Image->id));
			// Usually you will only assign something here if the file could not be uploaded.
			$message = '';
		} else {
			$message = __('Upload failed.');
		}
		$this->set(compact('funcNum', 'url', 'message'));
	}

	private function __getExtension($filename) {
		return strtolower(end(explode('.', $filename)));
	}

	public function browse($questionId) {
		$images = $this->Image->find(
			'all', array(
				'conditions' => array(
					'Image.question_id' => $questionId
				)
			)
		);
		$this->set(compact('images'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Image->id = $id;
		if (!$this->Image->exists()) {
			throw new NotFoundException(__('Invalid image'));
		}
		if ($this->Image->delete()) {
			$this->setFlashSuccess(__('Image deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->setFlashError(__('Image was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}