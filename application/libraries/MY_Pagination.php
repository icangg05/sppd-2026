<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Paginasi ringkas untuk semua halaman:
 *
 *     [<]  [ 3 ]  [>]   Halaman [ 3 ] dari 120
 *
 * Menggantikan tampilan nomor halaman bawaan CI (First / Previous / 1 2 3 / Next / Last).
 * Semua opsi URL tetap dipakai (base_url, first_url, uri_segment, use_page_numbers,
 * page_query_string, query_string_segment, reuse_query_string, prefix, suffix), sedangkan
 * opsi tampilan (full_tag_open, cur_tag_open, prev_link, dst.) di controller diabaikan.
 *
 * Kotak "Halaman" bisa diketik nomor halaman lalu Enter; skripnya ada di templates/_parts/footer.php.
 */
class MY_Pagination extends CI_Pagination {

	public function create_links()
	{
		// Note: jangan ganti operator menjadi === (sama seperti CI)
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		$num_pages = (int) ceil($this->total_rows / $this->per_page);

		if ($num_pages === 1)
		{
			return '';
		}

		// ---- Susun base_url & first_url (meniru CI_Pagination::create_links) ----
		if ($this->reuse_query_string === TRUE)
		{
			$get = $this->CI->input->get();
			unset($get['c'], $get['m'], $get[$this->query_string_segment]);
		}
		else
		{
			$get = array();
		}

		$base_url  = trim($this->base_url);
		$first_url = $this->first_url;
		$suffix    = $this->suffix; // variabel lokal: CI menambah suffix ke properti setiap kali dipanggil

		$query_string_sep = (strpos($base_url, '?') === FALSE) ? '?' : '&';

		if ($this->page_query_string === TRUE)
		{
			if ($first_url === '')
			{
				$first_url = $base_url;

				if ( ! empty($get))
				{
					$first_url .= $query_string_sep.http_build_query($get);
				}
			}

			$base_url .= $query_string_sep.http_build_query(array_merge($get, array($this->query_string_segment => '')));
		}
		else
		{
			$query_string = '';

			if ( ! empty($get))
			{
				$query_string = $query_string_sep.http_build_query($get);
				$suffix .= $query_string;
			}

			if ($this->reuse_query_string === TRUE && ($base_query_pos = strpos($base_url, '?')) !== FALSE)
			{
				$base_url = substr($base_url, 0, $base_query_pos);
			}

			if ($first_url === '')
			{
				$first_url = $base_url.$query_string;
			}

			$base_url = rtrim($base_url, '/').'/';
		}

		// ---- Tentukan halaman aktif ----
		$base_page = ($this->use_page_numbers) ? 1 : 0;

		if ($this->page_query_string === TRUE)
		{
			$this->cur_page = (string) $this->CI->input->get($this->query_string_segment);
		}
		elseif (empty($this->cur_page))
		{
			if ($this->uri_segment === 0)
			{
				$this->uri_segment = count($this->CI->uri->segment_array());
			}

			$this->cur_page = (string) $this->CI->uri->segment($this->uri_segment);

			if ($this->prefix !== '' OR $suffix !== '')
			{
				$this->cur_page = str_replace(array($this->prefix, $suffix), '', $this->cur_page);
			}
		}
		else
		{
			$this->cur_page = (string) $this->cur_page;
		}

		if ( ! ctype_digit($this->cur_page) OR ($this->use_page_numbers && (int) $this->cur_page === 0))
		{
			$this->cur_page = $base_page;
		}
		else
		{
			$this->cur_page = (int) $this->cur_page;
		}

		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		elseif ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}

		// Ubah offset menjadi nomor halaman
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = (int) floor(($this->cur_page / $this->per_page) + 1);
		}

		$cur = $this->cur_page;

		// ---- URL tiap halaman ----
		$url_tpl = $base_url.$this->prefix.'__HAL__'.$suffix;

		$url_halaman = function ($n) use ($base_page, $first_url, $url_tpl) {
			$i = $this->use_page_numbers ? $n : ($n - 1) * $this->per_page;
			return ($i === $base_page) ? $first_url : str_replace('__HAL__', $i, $url_tpl);
		};

		// ---- Render ----
		$attr = $this->_attributes;

		if ($cur > 1)
		{
			$prev = '<a class="pg-panah" href="'.$this->_esc_url($url_halaman($cur - 1)).'"'.$attr
				.' '.$this->data_page_attr.'="'.($cur - 1).'" rel="prev" aria-label="Halaman sebelumnya" title="Halaman sebelumnya">'
				.'<i class="fas fa-chevron-left" aria-hidden="true"></i></a>';
		}
		else
		{
			$prev = '<span class="pg-panah pg-mati" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>';
		}

		if ($cur < $num_pages)
		{
			$next = '<a class="pg-panah" href="'.$this->_esc_url($url_halaman($cur + 1)).'"'.$attr
				.' '.$this->data_page_attr.'="'.($cur + 1).'" rel="next" aria-label="Halaman berikutnya" title="Halaman berikutnya">'
				.'<i class="fas fa-chevron-right" aria-hidden="true"></i></a>';
		}
		else
		{
			$next = '<span class="pg-panah pg-mati" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>';
		}

		$input = '<input type="number" class="pg-input" inputmode="numeric" min="1" max="'.$num_pages.'" value="'.$cur.'"'
			.' aria-label="Nomor halaman, tekan Enter untuk pindah"'
			.' data-hal="'.$cur.'"'
			.' data-url="'.$this->_esc_url($url_tpl).'"'
			.' data-url-awal="'.$this->_esc_url($first_url).'"'
			.' data-per-hal="'.(int) $this->per_page.'"'
			.' data-nomor="'.($this->use_page_numbers ? 1 : 0).'">';

		return '<nav class="pg-ringkas" aria-label="Navigasi halaman">'
			.$prev
			.'<span class="pg-aktif" aria-current="page">'.$cur.'</span>'
			.$next
			.'<label class="pg-loncat"><span>Halaman</span>'.$input.'<span>dari '.$num_pages.'</span></label>'
			.'</nav>';
	}

	/**
	 * Rapikan URL (buang garis miring ganda, samakan &amp;) lalu escape untuk atribut HTML.
	 */
	protected function _esc_url($url)
	{
		$url = str_replace('&amp;', '&', $url);
		$url = preg_replace('#([^:])//+#', '\\1/', $url);
		return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
	}
}
