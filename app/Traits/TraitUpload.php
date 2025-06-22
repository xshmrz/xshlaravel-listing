<?php
    namespace App\Traits;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use EnumUpload;
    trait TraitUpload {
        public static function bootTraitUpload() {
            static::saving(function ($model) {
                if (\Schema::hasColumn($model->getTable(), 'upload')) {
                    $model->handleUploads(request()->allFiles());
                }
            });
            static::deleting(function ($model) {
                if (\Schema::hasColumn($model->getTable(), 'upload')) {
                    $model->deleteAllUploadedFiles();
                }
            });
        }
        protected function handleUploads(array $files) : void {
            $uploads = $this->upload ?? [];
            foreach ($files as $type => $file) {
                if (!EnumUpload::hasValue($type)) {
                    continue;
                }
                if (EnumUpload::multiple($type)) {
                    $paths = [];
                    foreach ((array) $file as $item) {
                        $paths[] = $this->storeFile($item, $type);
                    }
                    $uploads[$type] = $paths;
                }
                else {
                    if (!empty($uploads[$type])) {
                        Storage::disk('public')->delete($uploads[$type]);
                    }
                    $uploads[$type] = $this->storeFile($file, $type);
                }
            }
            $this->upload = $uploads;
        }
        protected function storeFile(UploadedFile $file, string $type) : string {
            $path     = 'uploads/'.Str::snake(class_basename($this)).'/'.$type;
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            return $file->storeAs($path, $filename, 'public');
        }
        protected function deleteAllUploadedFiles() : void {
            $uploads = $this->upload ?? [];
            foreach ($uploads as $type => $files) {
                $files = is_array($files) ? $files : [$files];
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
        }
        public function getUploadUrl(string $type) : ?string {
            if (!isset($this->upload[$type])) return null;
            return Storage::url($this->upload[$type]);
        }
        public function getUploadUrls(string $type) : array {
            if (!isset($this->upload[$type])) return [];
            return collect($this->upload[$type])->map(fn($f) => Storage::url($f))->toArray();
        }
    }
