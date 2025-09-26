<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
        });

        // Backfill de slugs para productos existentes
        $rows = DB::table('productos')->select('id','nombre','slug')->orderBy('id')->get();
        foreach ($rows as $row) {
            if ($row->slug) continue;

            $base = Str::slug($row->nombre) ?: 'producto-'.$row->id;
            $slug = $base;
            $i = 2;
            while (DB::table('productos')->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }

            DB::table('productos')->where('id', $row->id)->update(['slug' => $slug]);
        }

        // Por si quieres hacer obligatorio a futuro:
        // Schema::table('productos', function (Blueprint $table) {
        //     $table->string('slug')->unique()->nullable(false)->change();
        // });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
