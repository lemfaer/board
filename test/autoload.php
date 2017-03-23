<?php

spl_autoload_register(function ($cls) {
	$cls = "\\" . ltrim($cls, "\\");
	$cls = str_replace("\\Board\\Test\\", "\\", $cls);

	$rel = str_replace("\\", DIRECTORY_SEPARATOR, $cls);
	$abs = implode(DIRECTORY_SEPARATOR, [ __DIR__, $rel ]) . ".php";

	if (file_exists($abs)) {
		require $abs;
	}
});
