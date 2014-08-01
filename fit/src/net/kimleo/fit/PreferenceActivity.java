package net.kimleo.fit;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;

import java.util.Calendar;

/**
 * Created by Kim Leo on 14-3-28.
 */
public class PreferenceActivity extends Activity {

    private SharedPreferences perf;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.preference);
        perf = getSharedPreferences("PREFERENCE", Context.MODE_PRIVATE);

        EditText edit = (EditText) findViewById(R.id.editText);
        edit.setText(perf.getString("NAME", "YOU"));
        edit = (EditText) findViewById(R.id.editText2);
        final Calendar c = Calendar.getInstance();
        edit.setText(perf.getString("BIRTH", ""
                + c.get(Calendar.YEAR)
                + c.get(Calendar.MONTH)
                + c.get(Calendar.DAY_OF_MONTH)));
        edit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText editText = (EditText) (PreferenceActivity.this.findViewById(R.id.editText2));
                String[] split = editText.getText().toString().split("-");
                Calendar c = Calendar.getInstance();
                int year = c.get(Calendar.YEAR), month = c.get(Calendar.MONTH) + 1, day = c.get(Calendar.DAY_OF_MONTH);
                if (split.length == 3) {
                    year = Integer.parseInt(split[0]);
                    month = Integer.parseInt(split[1]);
                    day = Integer.parseInt(split[2]);
                }
                new DatePickerDialog(view.getContext(), new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker datePicker, int i, int i2, int i3) {
                        setBirthDate(String.format("%d-%d-%d", i, i2 + 1, i3));
                    }
                }, year, month - 1, day).show();
            }
        });

        Button btn = (Button) findViewById(R.id.button);
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                SharedPreferences.Editor editor = perf.edit();
                editor.putString("NAME", ((EditText) PreferenceActivity.this.findViewById(R.id.editText)).getText().toString());
                editor.putString("BIRTH", ((EditText) PreferenceActivity.this.findViewById(R.id.editText2)).getText().toString());
                editor.commit();
                finish();
            }
        });
    }

    private void setBirthDate(String text) {
        EditText editor = (EditText) findViewById(R.id.editText2);
        editor.setText(text);
    }
}