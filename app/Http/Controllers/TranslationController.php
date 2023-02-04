<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $translations = Translation::when($request->key, function ($query) use ($request) {
            $query->where('key', 'LIKE', '%' . $request->key . '%');
        })->when($request->role, function ($query) use ($request) {
            $query->where('role', $request->role);
        })->orderBy('created_at', 'desc')->paginate($per_page);
        return $translations;
    }

    public function getFile(Request $request)
    {
        if ($request->has('general')) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/general_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/general_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/general_other.json')));
            return $data;
        }
        if ($request->has('manager')) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/manager_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/manager_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/manager_other.json')));
            return $data;
        }
        if ($request->has('superadmin')) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/superadmin_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/superadmin_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/superadmin_other.json')));
            return $data;
        }
        if ($request->has('employee')) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/employee_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/employee_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/employee_other.json')));
            return $data;
        }
    }

    public function download_csv()
    {
        $translations = Translation::all();
        $file = "translations.lan";

        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, 'Key;English;Note;TranslatedLanguage;TranslatedSuggestion;Autor;LastEditedBy;LastEditedDate;Extra;WordKind;');
        fwrite($txt, "\r\n");
        fclose($txt);
        $txt = fopen($file, "a") or die("Unable to open file!");

        foreach ($translations as $task) {
            $row['Key']  = $task->key;
            $row['English']  = $task->english;
            $row['Note']  = '';
            $row['TranslatedLanguage']  = $task->german;
            $row['TranslatedSuggestion']  = $task->other;
            $row['Autor']  = '';
            $row['LastEditedBy']  = '';
            $row['LastEditedDate']  = '';
            $row['Extra']  = '';
            $row['WordKind']  = '';
            $new_row = implode(';', $row);
            $new_row = $new_row . ';';
            fwrite($txt, $new_row);
            fwrite($txt, "\r\n");
        }
        fclose($txt);
        $tranFIle = file_get_contents($file);
        $utf16le_text = mb_convert_encoding($tranFIle, "UTF-16LE");
        $response = new Response($utf16le_text);
        $response->headers->set('Content-Type', 'text/plain; charset=UTF-16LE');
        $response->headers->set('Content-Disposition', 'attachment; filename="translations.lan"');
        return $response;
    }

    public function upload_csv(Request $request)
    {
        $file = $request->file('file');
        $fopen = fopen($file, 'r');
        $fread = fread($fopen, filesize($file));
        fclose($fopen);
        $utf16le_text = mb_convert_encoding($fread, "UTF-8", "UTF-16LE");

        $remove = "\r\n";
        $split = explode($remove, $utf16le_text);
        $delimeter = ";";

        foreach ($split as $string) {
            $row = explode($delimeter, $string);
            if (sizeof($row) > 5) {
                $exist_key = Translation::where('key', $row[0])->first();
                if ($exist_key) {
                    $exist_key->update(['english' => $row[1], 'german' => $row[3], 'other' => $row[4]]);
                } else {
                    Translation::firstOrCreate(
                        ['key' => $row[0]],
                        ['english' => $row[1], 'german' => $row[3], 'other' => $row[4]]
                    );
                }
            }
        }
        return '';
    }

    public function refresh_files(Request $request)
    {
        file_put_contents(public_path('assets/translation/english/general_english.json'), null);
        file_put_contents(public_path('assets/translation/german/general_german.json'), null);
        file_put_contents(public_path('assets/translation/other/general_other.json'), null);
        $gen_trans = Translation::where('role', 'general')->get();

        $gen_eng_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_eng_trans[$t_value->key] = $t_value->english;
        }
        $fp = fopen(public_path('assets/translation/english/general_english.json'), 'a');
        fwrite($fp,  json_encode($gen_eng_trans));
        fclose($fp);


        $gen_ger_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_ger_trans[$t_value->key] = $t_value->german;
        }
        $fp = fopen(public_path('assets/translation/german/general_german.json'), 'a');
        fwrite($fp,  json_encode($gen_ger_trans));
        fclose($fp);


        $gen_other_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_other_trans[$t_value->key] = $t_value->other;
        }
        $fp = fopen(public_path('assets/translation/other/general_other.json'), 'a');
        fwrite($fp,  json_encode($gen_other_trans));
        fclose($fp);


        file_put_contents(public_path('assets/translation/english/employee_english.json'), null);
        file_put_contents(public_path('assets/translation/german/employee_german.json'), null);
        file_put_contents(public_path('assets/translation/other/employee_other.json'), null);
        $gen_trans = Translation::where('role', 'manager')->get(); //TODO: if i have change role

        $gen_eng_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_eng_trans[$t_value->key] = $t_value->english;
        }
        $fp = fopen(public_path('assets/translation/english/employee_english.json'), 'a');
        fwrite($fp,  json_encode($gen_eng_trans));
        fclose($fp);


        $gen_ger_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_ger_trans[$t_value->key] = $t_value->german;
        }
        $fp = fopen(public_path('assets/translation/german/employee_german.json'), 'a');
        fwrite($fp,  json_encode($gen_ger_trans));
        fclose($fp);


        $gen_other_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_other_trans[$t_value->key] = $t_value->other;
        }
        $fp = fopen(public_path('assets/translation/other/employee_other.json'), 'a');
        fwrite($fp,  json_encode($gen_other_trans));
        fclose($fp);



        file_put_contents(public_path('assets/translation/english/manager_english.json'), null);
        file_put_contents(public_path('assets/translation/german/manager_german.json'), null);
        file_put_contents(public_path('assets/translation/other/manager_other.json'), null);
        $gen_trans = Translation::where('role', 'manager')->get();

        $gen_eng_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_eng_trans[$t_value->key] = $t_value->english;
        }
        $fp = fopen(public_path('assets/translation/english/manager_english.json'), 'a');
        fwrite($fp,  json_encode($gen_eng_trans));
        fclose($fp);


        $gen_ger_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_ger_trans[$t_value->key] = $t_value->german;
        }
        $fp = fopen(public_path('assets/translation/german/manager_german.json'), 'a');
        fwrite($fp,  json_encode($gen_ger_trans));
        fclose($fp);


        $gen_other_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_other_trans[$t_value->key] = $t_value->other;
        }
        $fp = fopen(public_path('assets/translation/other/manager_other.json'), 'a');
        fwrite($fp,  json_encode($gen_other_trans));
        fclose($fp);



        file_put_contents(public_path('assets/translation/english/superadmin_english.json'), null);
        file_put_contents(public_path('assets/translation/german/superadmin_german.json'), null);
        file_put_contents(public_path('assets/translation/other/superadmin_other.json'), null);
        $gen_trans = Translation::where('role', 'superadmin')->get();

        $gen_eng_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_eng_trans[$t_value->key] = $t_value->english;
        }
        $fp = fopen(public_path('assets/translation/english/superadmin_english.json'), 'a');
        fwrite($fp,  json_encode($gen_eng_trans));
        fclose($fp);


        $gen_ger_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_ger_trans[$t_value->key] = $t_value->german;
        }
        $fp = fopen(public_path('assets/translation/german/superadmin_german.json'), 'a');
        fwrite($fp,  json_encode($gen_ger_trans));
        fclose($fp);


        $gen_other_trans = array();
        foreach ($gen_trans as $t_value) {
            $gen_other_trans[$t_value->key] = $t_value->other;
        }
        $fp = fopen(public_path('assets/translation/other/superadmin_other.json'), 'a');
        fwrite($fp,  json_encode($gen_other_trans));
        fclose($fp);
        return $gen_trans;
    }

    public function store(StoreTranslationRequest $request)
    {
        $translation          = new Translation();
        $translation->key     = $request->key;
        $translation->english = $request->english;
        if ($request->has('german') && $request->filled('german')) $translation->german  = $request->german;
        if ($request->has('other') && $request->filled('other')) $translation->other   = $request->other;
        if ($request->has('role'))
            $translation->role    = $request->role;
        $translation->addFlag(Translation::FLAG_ACTIVE);
        if ($translation->save()) return $translation;

        return response('Translation not added', 500);
    }

    public function show(Translation $translation)
    {
        return $translation;
    }

    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        $translation->key     = $request->input('key', $translation->key);
        $translation->english = $request->input('english', $translation->english);
        $translation->german  = $request->input('german', $translation->german);
        $translation->other   = $request->input('other', $translation->other);
        if ($translation->save())
            return $translation;
        return response('Translation not added', 500);
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        return response('Translation deleted', 200);
    }
}
