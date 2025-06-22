<?php
    class DataTableBuilder {
        private string $tableId    = 'datatable';
        private string $tableClassName = 'table table-striped-columns table-vcenter';
        private array  $tableData  = [];
        private string $ajaxUrl    = '';
        private bool   $isPlain    = false;

        private array  $cols       = [];
        private array  $col        = [];

        // Tablo özellikleri
        public function setId(string $id): self {
            $this->tableId = $id;
            return $this;
        }

        public function setClass(string $class): self {
            $this->tableClassName = $class;
            return $this;
        }

        public function setData(array $data): self {
            $this->tableData = $data;
            return $this;
        }

        public function setAjax(string $url): self {
            $this->ajaxUrl = $url;
            return $this;
        }

        public function setPlain(): self {
            $this->isPlain = true;
            return $this;
        }

        // Kolon özellikleri
        public function addCol(string $type = 'text'): self {
            $this->col = ['type' => $type];
            return $this;
        }

        public function title(string $text): self {
            $this->col['title'] = $text;
            return $this;
        }

        public function key(string $dataKey): self {
            $this->col['data'] = $dataKey;
            return $this;
        }

        public function class(string $class): self {
            $this->col['class'] = $class;
            return $this;
        }

        public function style(string $style): self {
            $this->col['style'] = $style;
            return $this;
        }

        public function callback(callable $fn): self {
            $this->col['callback'] = $fn;
            return $this;
        }

        public function options(array $opts): self {
            $this->col['options'] = $opts;
            return $this;
        }

        public function done(): self {
            $this->cols[] = $this->col;
            $this->col = [];
            return $this;
        }

        public function render(): string {
            $html = "<table id=\"{$this->tableId}\" class=\"{$this->tableClassName}\" style=\"min-width:800px\"><thead><tr>";
            foreach ($this->cols as $col) {
                $class = $col['class'] ?? '';
                $style = $col['style'] ?? '';
                $title = htmlspecialchars($col['title'] ?? '');
                $html  .= "<th class=\"{$class}\" style=\"{$style}\">{$title}</th>";
            }
            $html .= "</tr></thead><tbody>";

            foreach ($this->tableData as $row) {
                $html .= "<tr>";
                foreach ($this->cols as $col) {
                    $type  = $col['type'];
                    $key   = $col['data'] ?? null;
                    $class = $col['class'] ?? '';
                    $style = $col['style'] ?? '';
                    $cell  = '';
                    switch ($type) {
                        case 'html':
                            $cell = call_user_func($col['callback'], $row);
                            break;
                        case 'switch':
                            $on    = $col['options']['on'] ?? 'On';
                            $off   = $col['options']['off'] ?? 'Off';
                            $route = $col['options']['route'] ?? '#';
                            $url   = is_callable($route) ? call_user_func($route, $row) : $route;
                            $val   = $key && isset($row[$key]) ? $row[$key] : 0;
                            $check = $val ? 'checked' : '';
                            $cell  = "<label class='form-switch'>
                                    <input type='checkbox' class='form-check-input datatable-switch' data-url='{$url}' {$check}>
                                    <span class='form-check-label'>".($val ? $on : $off)."</span>
                                  </label>";
                            break;
                        case 'checkbox':
                            $cell = call_user_func($col['callback'], $row)
                                ? "<input type='checkbox' checked>"
                                : "<input type='checkbox'>";
                            break;
                        case 'dropdown':
                            $items = call_user_func($col['callback'], $row);
                            $cell  = "<div class='dropdown'><button class='btn btn-sm btn-secondary dropdown-toggle' data-bs-toggle='dropdown'>Seç</button><ul class='dropdown-menu'>";
                            foreach ($items as $label => $url) {
                                $cell .= "<li><a class='dropdown-item' href='{$url}'>{$label}</a></li>";
                            }
                            $cell .= "</ul></div>";
                            break;
                        default:
                            $val  = isset($col['data'], $row[$col['data']]) ? $row[$col['data']] : '';
                            $cell = htmlspecialchars((string) $val);
                    }
                    $html .= "<td class=\"{$class}\" style=\"{$style}\">{$cell}</td>";
                }
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";

            // Sade tablo ise DataTable script yok
            if ($this->isPlain) {
                return $html;
            }

            // DataTable Script
            $defs = [];
            foreach ($this->cols as $i => $col) {
                if (!empty($col['class'])) {
                    $defs[] = "{'targets': {$i}, 'className': '{$col['class']}'}";
                }
            }

            $columns = [];
            foreach ($this->cols as $col) {
                $columns[] = ($col['type'] === 'text' && !empty($col['data']))
                    ? "{'data': '{$col['data']}'}"
                    : "{}";
            }

            $html .= "
            <script>
            $(function() {
                $('#{$this->tableId}').DataTable({".($this->ajaxUrl ? "'ajax': { 'url': '{$this->ajaxUrl}', 'type': 'GET' }," : '')."
                    'columns'       : [".implode(',', $columns)."],
                    'columnDefs'    : [".implode(',', $defs)."],
                    'lengthMenu'    : false,
                    'searching'     : true,
                    'pageLength'    : 10,
                    'processing'    : false,
                    'autoWidth'     : false,
                    'ordering'      : true,
                    'order'         : [[]], // 1. kolon = Ad Soyad, desc sırala
                    'dom'           : '<\"table-responsive\"t><\"bg-body-light rounded py-2 mt-0\"p>',
                    'language'      : {
                        'lengthMenu'        : '_MENU_',
                        'search'            : '_INPUT_',
                        'searchPlaceholder' : 'Search...',
                        'info'              : 'Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>',
                        'processing'        : 'Loading',
                        'paginate': {
                            'first'     : '<i class=\"fa fa-angle-double-left\"></i>',
                            'previous'  : '<i class=\"fa fa-angle-left\"></i>',
                            'next'      : '<i class=\"fa fa-angle-right\"></i>',
                            'last'      : '<i class=\"fa fa-angle-double-right\"></i>'
                        }
                    }
                });

                $(document).on('change', '.datatable-switch', function() {
                    fetch($(this).data('url'), {
                        method: 'POST',
                        headers: {'X-Requested-With': 'XMLHttpRequest'}
                    });
                });
            });
            </script>\n";
            return $html;
        }
    }
