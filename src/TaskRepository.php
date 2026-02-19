<?php
declare(strict_types=1);

final class TaskRepository
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($path)) {
            file_put_contents($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /** @return array<int, array{id:int,title:string,done:bool,created_at:string}> */
    public function all(): array
    {
        $data = $this->read();
        // Ordena por id desc (mais nova primeiro)
        usort($data, fn($a, $b) => $b['id'] <=> $a['id']);
        return $data;
    }

    public function add(string $title): void
    {
        $data = $this->read();
        $nextId = empty($data) ? 1 : (max(array_column($data, 'id')) + 1);

        $data[] = [
            'id' => $nextId,
            'title' => $title,
            'done' => false,
            'created_at' => date('c'),
        ];

        $this->write($data);
    }

    public function toggle(int $id): void
    {
        $data = $this->read();
        foreach ($data as &$t) {
            if ((int)$t['id'] === $id) {
                $t['done'] = !((bool)$t['done']);
                break;
            }
        }
        $this->write($data);
    }

    public function delete(int $id): void
    {
        $data = $this->read();
        $data = array_values(array_filter($data, fn($t) => (int)$t['id'] !== $id));
        $this->write($data);
    }

    /** @return array<int, array<string,mixed>> */
    private function read(): array
    {
        $raw = (string)file_get_contents($this->path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    /** @param array<int, array<string,mixed>> $data */
    private function write(array $data): void
    {
        file_put_contents(
            $this->path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
