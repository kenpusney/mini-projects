package net.kimleo.fit;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.Toast;

import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;

public class SplashActivity extends Activity {


    private String[] menuTitles = new String[]{
            "New Record", "New Exercise", "Statistics", "Preference"
    };
    private String[] menuDescs = new String[]{
            "add new fit-record",
            "start a new exercise",
            "see your all statistics",
            "set your personal preference"
    };
    Class[] cls = new Class[]{
            NewRecordActivity.class,
            null,
            StatisticsActivity.class,
            PreferenceActivity.class
    };

    private List<HashMap<String, String>> menuInfo;

    private AdapterView.OnItemClickListener listItemClickListener = new AdapterView.OnItemClickListener() {
        @Override
        public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
            if (cls[i] != null)
                startActivity(new Intent(view.getContext(), cls[i]));
            else
                Toast.makeText(SplashActivity.this,
                        "Not implemented yet!",
                        Toast.LENGTH_SHORT).show();
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);

        makeMenuInfo();

        ListView listView = (ListView) findViewById(R.id.listView);
        listView.setAdapter(new SimpleAdapter(getApplicationContext(),
                menuInfo,
                R.layout.menu_item,
                new String[]{"MenuTitle", "MenuDescription"},
                new int[]{R.id.menuTitle, R.id.menuDesc}));

        listView.setOnItemClickListener(listItemClickListener);
    }

    private void makeMenuInfo() {
        menuInfo = new LinkedList<HashMap<String, String>>();
        for (int i = 0; i < menuTitles.length; i++) {
            HashMap<String, String> menuItem = new HashMap<String, String>();
            menuItem.put("MenuTitle", menuTitles[i]);
            menuItem.put("MenuDescription", menuDescs[i]);
            menuInfo.add(menuItem);
        }
    }
}
